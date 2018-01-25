<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Box;
use Session;

class BoxController extends Controller
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
        $boxes = Box::with('products')->orderBy('id', 'desc')->paginate(20);

        return view('admin.boxes.index', ['boxes' => $boxes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.boxes.create');
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
            'name'          => 'required|string|max:191',
            'products_id'   => 'nullable|int_array',
            'units'         => 'nullable|int_array',
        ]);

        $box = new Box;
        $box->name = $request->name;
        $box->express = 0;
        $box->save();

        $products = [];
        $p = $request->products_id;
        $u = $request->units;
        for ($i=0; $i<min(count($p), count($u)); $i++)
            $products[$p[$i]] = [
                'unit' => $u[$i],
                'stock' => 0,
                'total_stock' => 0,
                'total_cost' => 0
            ];
        $box->products()->sync($products);

        Session::flash('status' , '库存 <' . $box->name . '> 创建成功！');

        return redirect()->route('admin.boxes.show', $box->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $box = Box::find($id);

        return view('admin.boxes.show', ['box' => $box]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $box = Box::find($id);

        return view('admin.boxes.edit', ['box' => $box]);
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
            'name'          => 'required|string|max:191',
            'products_id'   => 'nullable|int_array',
            'units'         => 'nullable|int_array',
        ]);

        $box = Box::find($id);
        $box->name = $request->name;
        $box->save();

        if ($box->express == 0) {
            $products = [];
            $p = $request->products_id;
            $u = $request->units;
            for ($i = 0; $i < min(count($p), count($u)); $i++)
                $products[$p[$i]] = [
                    'unit' => $u[$i],
                    'stock' => 0,
                    'total_stock' => 0,
                    'total_cost' => 0
                ];
            $box->products()->sync($products);
        }

        Session::flash('status' , '库存 <' . $box->name . '> 修改成功！');

        return redirect()->route('admin.boxes.show', $box->id);
    }

    public function update_product(Request $request, $box_id, $product_id)
    {
        $this->validate($request, [
            'stock' => 'required|integer|min:0',
            'cost'  => 'required|numeric|min:0',
        ]);

        $box = Box::find($box_id);
        $product = $box->product($product_id)->first();
        $product->incrementStock($request->stock);
        $product->incrementCost($request->cost);

        Session::flash('status' , '库存 <' . $box->name . '> 修改成功！');

        return redirect()->route('admin.boxes.show', $box->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
