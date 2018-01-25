<?php

namespace App\Http\Controllers;

use App\Tag;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banner_tag = Tag::find(1);
        $feature_tag = Tag::find(2);
        $banner_posts = empty($banner_tag) ? [] : $banner_tag->articles;
        $feature_posts = empty($feature_tag) ? [] : $feature_tag->articles;

        return view('home', ['banner_posts' => $banner_posts, 'feature_posts' => $feature_posts]);
    }
}
