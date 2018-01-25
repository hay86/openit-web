@extends('layouts.app')

@section('title', $box->name)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>{{ $box->name }}</small></h1>
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
                            <th>名称</th>
                            <th>状态</th>
                            <th>单位</th>
                            <th>库存</th>
                            <th>总库存</th>
                            <th>定价</th>
                            <th>成本</th>
                            <th>总成本</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($box->products as $product)
                            <tr>
                                <th>{{ $product->id }}</th>
                                <td>{{ $product->displayName }}</td>
                                @if ($product->deleted_at)
                                    <td><span class="label label-danger">私有</span></td>
                                @else
                                    <td><span class="label label-success">公开</span></td>
                                @endif
                                <td>{{ $product->unit }}份</td>
                                <td>{{ $product->stock }}份</td>
                                <td>{{ $product->total_stock }}份</td>
                                <td>¥{{ $product->price }}</td>
                                <td>¥{{ $product->unit_cost }}</td>
                                <td>¥{{ $product->total_cost }}</td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.products.show', $product->id) }}">查看</a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" class="text-right">
                                    <form class="form-inline" role="form" method="POST" action="{{ route('admin.boxes.update.product', [$box->id, $product->id]) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}
                                        <div class="form-group">
                                            <label class="sr-only" for="stock">库存</label>
                                            <input type="number" class="form-control" id="stock" name="stock" placeholder="加库存（份）">
                                        </div>
                                        <div class="form-group">
                                            <label class="sr-only" for="cost">成本</label>
                                            <input type="number" step="0.01" class="form-control" id="cost" name="cost" placeholder="加成本（元）">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-danger">增加</button>
                                    </form>
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
                        <div class="form-group">
                            <a href="{{ route('admin.boxes.edit', $box->id) }}" class="btn btn-primary btn-block">编辑库存</a>
                        </div>
                        <a href="{{ route('admin.boxes.index') }}" class="btn btn-success btn-block">返回上一级</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection