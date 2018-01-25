@extends('layouts.app')

@section('title', $post->title)

@section('stylesheets')

    <style>
        .qrcode-lg { width:180px; position:fixed; top:60px; left:50%; margin-left:384px; }
        .qrcode-lg p { color: #333; }
        .qrcode-lg img { width: 120px; }
        .qrcode-md { width:160px; position:fixed; top:60px; left:50%; margin-left:317px; }
        .qrcode-md p { color: #333; }
        .qrcode-md img { width: 110px; }
    </style>

@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <img src="{{ $post->img_md_wide() }}" class="img-banner img-responsive">
                <blockquote>
                    <h2>{{ $post->title }}</h2>
                    <footer>{{ $post->user->name }} 发表于 {{ $post->created_at->diffForHumans() }}</footer>
                </blockquote>
                <div class="body">{!! $post->body !!}</div>
                @if (count($post->tags) > 0)
                <div class="tags">
                    <span class="glyphicon glyphicon-tags" aria-hidden="true"></span>
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag->id) }}"><span class="label label-default">{{ $tag->name }}</span></a>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="col-md-2">
                <ul class="list-group visible-lg-block qrcode-lg">
                    <li class="list-group-item text-center">
                        <p><img src="http://static.openit.shop/qrcode_258.jpg" alt="微信搜索公众号「Openit」"></p>
                        <p>微信扫一扫<br>关注Openit公众号</p>
                    </li>
                </ul>
                <ul class="list-group visible-md-block qrcode-md">
                    <li class="list-group-item text-center">
                        <p><img src="http://static.openit.shop/qrcode_258.jpg" alt="微信搜索公众号「Openit」"></p>
                        <p>微信扫一扫<br>关注Openit公众号</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>

@endsection