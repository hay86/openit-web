@extends('layouts.app')

@section('title', $article->title)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <img src="{{ $article->img_md_wide() }}" class="img-banner img-responsive">
                <blockquote>
                    <h2>{{ $article->title }}</h2>
                    <footer>{{ $article->user->name }} 发表于 {{ $article->created_at->diffForHumans() }}</footer>
                </blockquote>
                <div class="body">{!! $article->body !!}</div>
                @if (count($article->tags) > 0)
                <div class="tags">
                    <span class="glyphicon glyphicon-tags" aria-hidden="true"></span>
                    @foreach ($article->tags as $tag)
                        <a href="{{ route('admin.tags.show', $tag->id) }}"><span class="label label-default">{{ $tag->name }}</span></a>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">操作</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <a class="btn btn-primary btn-block" href="{{ route('admin.articles.edit', $article->id) }}">编辑文章</a>
                        </div>
                        @if ($article->deleted_at)
                            <form role="form" method="POST" action="{{ route('admin.articles.restore', $article->id) }}" class="public form-group">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-block">公开文章</button>
                            </form>
                        @else
                            <form role="form" method="POST" action="{{ route('admin.articles.destroy', $article->id) }}" class="private form-group">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-danger btn-block">私有文章</button>
                            </form>
                        @endif
                        <a class="btn btn-success btn-block" href="{{ route('admin.articles.index') }}">返回上一级</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(".private").on("submit", function(){
            return confirm("确定要私有这篇文章么？");
        });
        $(".public").on("submit", function(){
            return confirm("确定要公开这篇文章么？");
        });
    </script>
@endsection