<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use App\Tag;
use App\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Auth;

class ArticleController extends Controller
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
        $articles = Article::withTrashed()->with(['user', 'tags'])->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.articles.index', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();

        return view('admin.articles.create', ['tags' => $tags]);
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
            'title'     => 'required|string|max:191',
            'subtitle'  => 'required|string|max:191',
            'body'      => 'required|string',
            'image_url' => 'required|string',
            'tags_id'   => 'nullable|int_array',
        ]);

        $article            = new Article;
        $article->title     = $request->title;
        $article->subtitle  = $request->subtitle;
        $article->body      = $request->body;
        $article->user_id   = Auth::user()->id;
        $article->image_id  = Image::get_id($request->image_url);

        $article->save();

        $tag_ids = empty($request->tag_ids) ? [] : $request->tag_ids;
        $article->tags()->sync($tag_ids);

        $image_ids = Image::get_ids($article->body);
        if (!in_array($article->image_id, $image_ids)) $image_ids[] = $article->image_id;

        Image::where('article_id', Image::ORPHAN_ID)->whereIn('id', $image_ids)->update(['article_id' => $article->id]);
        Image::where('article_id', $article->id)->whereNotIn('id', $image_ids)->update(['article_id' => Image::ORPHAN_ID]);

        Article::find($article->id)->delete();  // set it private

        Session::flash('status' , '文章 <' . $article->title . '> 创建成功！');

        return redirect()->route('admin.articles.show', $article->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::withTrashed()->find($id);

        return view('admin.articles.show', ['article' => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::withTrashed()->find($id);
        $tags = Tag::all();

        return view('admin.articles.edit', ['article' => $article, 'tags' => $tags]);
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
            'title'     => 'required|string|max:191',
            'subtitle'  => 'required|string|max:191',
            'body'      => 'required|string',
            'image_url' => 'required|string',
            'tags_id'   => 'nullable|int_array',
        ]);

        $article            = Article::withTrashed()->find($id);
        $article->title     = $request->title;
        $article->subtitle  = $request->subtitle;
        $article->body      = $request->body;
        $article->image_id  = Image::get_id($request->image_url);

        $article->save();

        $tag_ids = empty($request->tag_ids) ? [] : $request->tag_ids;
        $article->tags()->sync($tag_ids);

        $image_ids = Image::get_ids($article->body);
        if (!in_array($article->image_id, $image_ids)) $image_ids[] = $article->image_id;

        Image::where('article_id', Image::ORPHAN_ID)->whereIn('id', $image_ids)->update(['article_id' => $article->id]);
        Image::where('article_id', $article->id)->whereNotIn('id', $image_ids)->update(['article_id' => Image::ORPHAN_ID]);

        Session::flash('status' , '文章 <' . $article->title . '> 修改成功！');

        return redirect()->route('admin.articles.show', $article->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);

        $article->delete();

        Session::flash('status' , '文章 <' . $article->title . '> 私有成功！');

        return redirect()->route('admin.articles.index');
    }

    public function restore($id)
    {
        Article::onlyTrashed()->where('id', $id)->restore();

        $article = Article::find($id);
        $timestamp = date('Y-m-d H:i:s');

        $article->created_at = $timestamp;
        $article->updated_at = $timestamp;
        $article->save();

        Session::flash('status' , '文章 <' . $article->title . '> 公开成功！');

        return redirect()->route('admin.articles.index');
    }
}
