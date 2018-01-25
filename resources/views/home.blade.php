@extends('layouts.app')

@section('title', '首页')

@section('stylesheets')
    <style>
        body {
            padding-top: 50px;
        }
        .space {
            padding-top: 12px;
        }
        .carousel .carousel-inner .item {
            height: 540px;
            background-size: cover;
            background-position: center;
        }

    </style>
@endsection

@section('content')

    @if (count($banner_posts) > 0)
        <!-- banner posts, show in PC -->
        <div id="carousel-generic" class="carousel slide hidden-xs hidden-sm" data-ride="carousel">

            <!-- Indicators -->
            <ol class="carousel-indicators">
                @for ($i=0; $i<count($banner_posts); $i++)
                    <li data-target="#carousel-generic" data-slide-to="{{ $i }}" class="{{ $i==0 ? 'active' : '' }}"></li>
                @endfor
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                @for ($i=0; $i<count($banner_posts); $i++)
                    <div class="item {{ $i==0 ? 'active' : '' }}" style="background-image: url('{{ $banner_posts[$i]->img_md_wide() }}')">
                        <a href="{{ route('blog.show', $banner_posts[$i]->id) }}">
                            <div class="carousel-caption">
                                <div class="carousel-title">
                                    <h1>{{ $banner_posts[$i]->title }}</h1>
                                    <p class="lead">{{ str_limit($banner_posts[$i]->subtitle, 60) }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endfor
            </div>
        </div>
    @endif

    <div class="space"></div>

    <!-- banner posts, show in mobile device -->
    <div class="container hidden-md hidden-lg">
        <div class="row">
            @foreach ($banner_posts as $post)
                <div class="col-md-4 post">
                    <a class="post-link" href="{{ route('blog.show', $post->id) }}">
                        <div class="post-img">
                            <div class="img-inner" style="background-image: url('{{ $post->img_sm_wide() }}')"></div>
                        </div>
                        <div class="post-text">
                            <div class="title">{{ $post->title }}</div>
                            <div class="subtitle">{{ str_limit($post->subtitle, 40) }}</div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- feature posts, show in all platform -->
    <div class="container">
        <div class="row">
            @foreach ($feature_posts as $post)
                <div class="col-md-4 post">
                    <a class="post-link" href="{{ route('blog.show', $post->id) }}">
                        <div class="post-img">
                            <div class="img-inner" style="background-image: url('{{ $post->img_sm_wide() }}')"></div>
                        </div>
                        <div class="post-text">
                            <div class="title">{{ $post->title }}</div>
                            <div class="subtitle">{{ str_limit($post->subtitle, 40) }}</div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

@endsection