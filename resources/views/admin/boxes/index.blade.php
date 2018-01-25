@extends('layouts.app')

@section('title', '所有库存')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>库存共 {{ $boxes->total() }} 个</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-primary">
                    <div class="panel-heading">库存列表</div>
                    <table class="table table-condensed table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>盒子名称</th>
                            <th>商品</th>
                            <th>库存</th>
                            <th>定价</th>
                            <th>成本</th>
                            <th>发货</th>
                            <th>创建于</th>
                            <th>修改于</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($boxes as $box)
                            <tr>
                                <th>{{ $box->id }}</th>
                                <td>{{ $box->name }}</td>
                                <td>{{ count($box->products) }}份</td>
                                <td>{{ $box->stock }}份</td>
                                <td>¥{{ $box->price }}</td>
                                <td>¥{{ $box->cost }}</td>
                                <td>{{ $box->express }}次</td>
                                <td>{{ $box->created_at->diffForHumans() }}</td>
                                <td>{{ $box->updated_at->diffForHumans() }}</td>
                                <td class="text-right">
                                    <a href="{{ route('admin.boxes.edit', $box->id) }}" class="btn btn-danger btn-sm">编辑</a>
                                    <a href="{{ route('admin.boxes.show', $box->id) }}" class="btn btn-primary btn-sm">查看</a>
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
                        <a href="{{ route('admin.boxes.create') }}" class="btn btn-primary btn-block">创建库存</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    {!! $boxes->links() !!}
                </div>
            </div>
        </div>
    </div>

@endsection