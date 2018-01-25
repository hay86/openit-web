<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use Session;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::withTrashed()->orderBy('id', 'desc')->paginate(20);

        return view('admin.products.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.products.create', ['types' => array_keys(product_type('*'))]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|string|max:191',
            'country'   => 'nullable|string|max:191',
            'price'     => 'required|numeric|min:0',
            'life'      => 'required|integer|min:0',
            'type'      => 'required|integer|min:0',
            'times'     => 'required|integer|min:0',
            'length'    => 'nullable|numeric|min:0',
            'width'     => 'nullable|numeric|min:0',
            'height'    => 'nullable|numeric|min:0',
            'weight'    => 'nullable|numeric|min:0',
            'sweetness' => 'nullable|numeric|min:-3|max:3',
            'hardness'  => 'nullable|numeric|min:-3|max:3',
            'article_id'=> 'required|integer',
            'image_id'  => 'nullable|string|max:191',
        ]);

        $product                = new Product;
        $product->name          = $request->name;
        $product->country       = $request->country;
        $product->price         = $request->price;
        $product->life          = $request->life;
        $product->type          = $request->type;
        $product->times         = $request->times;
        $product->length        = $request->length;
        $product->width         = $request->width;
        $product->height        = $request->height;
        $product->weight        = $request->weight;
        $product->sweetness     = $request->sweetness;
        $product->hardness      = $request->hardness;
        $product->article_id    = $request->article_id;
        $product->image_id      = $request->image_id;
        $product->save();

        Product::find($product->id)->delete();  // set it private

        Session::flash('status' , '商品 <' . $product->displayName . '> 创建成功！');

        return redirect()->route('admin.products.show', $product->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::withTrashed()->find($id);

        return view('admin.products.show', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::withTrashed()->find($id);

        return view('admin.products.edit', ['product' => $product, 'types' => array_keys(product_type('*'))]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'      => 'required|string|max:191',
            'country'   => 'nullable|string|max:191',
            'price'     => 'required|numeric|min:0',
            'life'      => 'required|integer|min:0',
            'type'      => 'required|integer|min:0',
            'times'     => 'required|integer|min:0',
            'length'    => 'nullable|numeric|min:0',
            'width'     => 'nullable|numeric|min:0',
            'height'    => 'nullable|numeric|min:0',
            'weight'    => 'nullable|numeric|min:0',
            'sweetness' => 'nullable|numeric|min:-3|max:3',
            'hardness'  => 'nullable|numeric|min:-3|max:3',
            'article_id'=> 'required|integer',
            'image_id'  => 'nullable|string|max:191',
        ]);

        $product                = Product::withTrashed()->find($id);
        $product->name          = $request->name;
        $product->country       = $request->country;
        $product->price         = $request->price;
        $product->life          = $request->life;
        $product->type          = $request->type;
        $product->times         = $request->times;
        $product->length        = $request->length;
        $product->width         = $request->width;
        $product->height        = $request->height;
        $product->weight        = $request->weight;
        $product->sweetness     = $request->sweetness;
        $product->hardness      = $request->hardness;
        $product->article_id    = $request->article_id;
        $product->image_id      = $request->image_id;
        $product->save();

        Session::flash('status' , '商品 <' . $product->displayName . '> 修改成功！');

        return redirect()->route('admin.products.show', $product->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        $product->delete();

        Session::flash('status' , '商品 <' . $product->displayName . '> 私有成功！');

        return redirect()->route('admin.products.index');
    }

    public function restore($id)
    {
        Product::onlyTrashed()->where('id', $id)->restore();

        $product = Product::find($id);
        $timestamp = date('Y-m-d H:i:s');

        $product->created_at = $timestamp;
        $product->updated_at = $timestamp;
        $product->save();

        Session::flash('status' , '商品 <' . $product->displayName . '> 公开成功！');

        return redirect()->route('admin.products.index');
    }
}
