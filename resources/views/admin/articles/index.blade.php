@extends('layouts.app')

@section('title', '所有文章')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>文章共 {{ $articles->total() }} 篇</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-primary">
                    <div class="panel-heading">文章列表</div>
                    <table class="table table-condensed table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>文章标题</th>
                            <th>作者</th>
                            <th>标签</th>
                            <th>状态</th>
                            <th>创建于</th>
                            <th>修改于</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($articles as $article)
                            <tr>
                                <th>{{ $article->id }}</th>
                                <td>{{ $article->title }}</td>
                                <td>{{ $article->user->name }}</td>
                                <td>
                                    @foreach ($article->tags as $tag)
                                        <span class="label label-default">{{ $tag->name }}</span>
                                    @endforeach
                                </td>
                                @if ($article->deleted_at)
                                    <td><span class="label label-danger">私有</span></td>
                                @else
                                    <td><span class="label label-success">公开</span></td>
                                @endif
                                <td>{{ $article->created_at->diffForHumans() }}</td>
                                <td>{{ $article->updated_at->diffForHumans() }}</td>
                                <td class="text-right">
                                    <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-danger btn-sm">编辑</a>
                                    <a href="{{ route('admin.articles.show', $article->id) }}" class="btn btn-primary btn-sm">查看</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">操作</div>
                    <div class="panel-body">
                        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary btn-block">创建文章</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    {!! $articles->links() !!}
                </div>
            </div>
        </div>
    </div>

@endsection