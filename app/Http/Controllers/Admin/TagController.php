<?php

namespace App\Http\Controllers\Admin;

use App\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;

class TagController extends Controller
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
        $tags = Tag::orderBy('id', 'asc')->paginate(20);;

        return view('admin.tags.index', ['tags' => $tags]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
           'name' => 'required|string|max:191|unique:tags,name',
        ]);

        $tag = new Tag;
        $tag->name = $request->name;
        $tag->save();

        Session::flash('status' , '标签 <' . $tag->name . '> 创建成功！');

        return redirect()->route('admin.tags.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = Tag::find($id);

        $articles = $tag->articles()->withTrashed()->paginate(20);

        return view('admin.tags.show', ['tag' => $tag, 'articles' => $articles]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:tags,name,' . $id,
        ]);

        $tag = Tag::find($id);
        $tag->name = $request->name;
        $tag->save();

        Session::flash('status' , '标签 <' . $tag->name . '> 修改成功！');

        return redirect()->route('admin.tags.show', $tag->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        $tag->articles()->withTrashed()->detach();
        $tag->delete();

        Session::flash('status' , '标签 <' . $tag->name . '> 删除成功！');

        return redirect()->route('admin.tags.index');
    }
}
