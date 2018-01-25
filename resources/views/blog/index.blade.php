@extends('layouts.app')

@section('active_blog', 'active')

@section('title', '博客')

@section('content')

    <div class="container">
        <div class="row">
            @foreach ($posts as $post)
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

        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    {!! $posts->links() !!}
                </div>
            </div>
        </div>
    </div>

@endsection