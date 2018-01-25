@extends('layouts.app')

@section('title', '所有标签')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>标签共 {{ $tags->total() }} 个</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-primary">
                    <div class="panel-heading">标签列表</div>
                    <table class="table table-condensed table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>标签名称</th>
                            <th>创建于</th>
                            <th>修改于</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($tags as $tag)
                            <tr>
                                <th>{{ $tag->id }}</th>
                                <td>{{ $tag->name }}</td>
                                <td>{{ $tag->created_at->diffForHumans() }}</td>
                                <td>{{ $tag->updated_at->diffForHumans() }}</td>
                                <td class="text-right">
                                    <a href="{{ route('admin.tags.show', $tag->id) }}" class="btn btn-danger btn-sm">编辑</a>
                                    <a href="{{ route('admin.tags.show', $tag->id) }}" class="btn btn-primary btn-sm">查看</a>
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
                        <form role="form" method="POST" action="{{ route('admin.tags.store') }}">
                            {{ csrf_field() }}
                            <div class="input-group">
                                <input id="name" name="name" type="text" class="form-control" placeholder="名称" value="{{ old('name') }}">
                                <span class="input-group-btn"><button type="submit" class="btn btn-primary">创建标签</button></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        {!! $tags->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection