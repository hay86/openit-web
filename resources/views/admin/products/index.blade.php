@extends('layouts.app')

@section('title', '所有商品')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>商品共 {{ $products->total() }} 件</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-primary">
                    <div class="panel-heading">商品列表</div>
                    <table class="table table-condensed table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>商品名称</th>
                            <th>定价</th>
                            <th>保质期</th>
                            <th>类型</th>
                            <th>状态</th>
                            <th>创建于</th>
                            <th>修改于</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <th>{{ $product->id }}</th>
                                <td>{{ $product->displayName }}</td>
                                <td>¥{{ $product->price }}</td>
                                <td>{{ shelf_life($product->life) }}</td>
                                <td>{{ product_type($product->type) }}</td>
                                @if ($product->deleted_at)
                                    <td><span class="label label-danger">私有</span></td>
                                @else
                                    <td><span class="label label-success">公开</span></td>
                                @endif
                                <td>{{ $product->created_at->diffForHumans() }}</td>
                                <td>{{ $product->updated_at->diffForHumans() }}</td>
                                <td class="text-right">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-danger btn-sm">编辑</a>
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-primary btn-sm">查看</a>
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
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-block">创建商品</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    {!! $products->links() !!}
                </div>
            </div>
        </div>
    </div>

@endsection