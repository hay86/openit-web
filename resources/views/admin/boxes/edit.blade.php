@extends('layouts.app')

@section('title', '编辑库存')

@section('stylesheets')

    <style>
        .products .left { padding-right:6px; }
        .products .right { padding-left:6px; }
    </style>

@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>编辑库存</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('admin.boxes.update', $box->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">盒子名称</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="name" name="name" value="{{ $box->name }}" placeholder="名称">
                        </div>
                    </div>
                    <div id="products" class="form-group products">
                        <label for="products_id" class="col-sm-4 control-label">关联商品</label>
                        @if (count($box->products) > 0)
                            @foreach ($box->products as $product)
                            <div class="{{ $loop->iteration > 1 ? 'col-sm-offset-4' : '' }} col-sm-2 left">
                                <input type="number" class="form-control col-sm-2" id="products_id" name="products_id[]" value="{{ $product->id }}" placeholder="数字ID" {{ $box->express > 0 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-sm-2 right">
                                <input type="number" class="form-control" id="units" name="units[]" value="{{ $product->unit }}" placeholder="单位数" {{ $box->express > 0 ? 'disabled' : '' }}>
                            </div>
                            @endforeach
                        @else
                            <div class="col-sm-2 left">
                                <input type="number" class="form-control col-sm-2" id="products_id" name="products_id[]" value="" placeholder="数字ID">
                            </div>
                            <div class="col-sm-2 right">
                                <input type="number" class="form-control" id="units" name="units[]" value="" placeholder="单位数">
                            </div>
                        @endif
                    </div>
                    @if ($box->express == 0)
                        <div class="form-group products">
                            <div class="col-sm-offset-4 col-sm-2 left">
                                <button id="add_product" type="button" class="btn btn-success btn-sm btn-block">加商品</button>
                            </div>
                            <div class="col-sm-2 right">
                                <button id="minus_product" type="button" class="btn btn-danger btn-sm btn-block">减商品</button>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-4">
                            <button id="save" type="submit" class="btn btn-primary">保存</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $(function(){
            var input = '<div class="col-sm-offset-4 col-sm-2 left"><input type="number" class="form-control" id="products_id" name="products_id[]" value="" placeholder="数字ID"></div>' +
                '<div class="col-sm-2 right"><input type="number" class="form-control" id="units" name="units[]" value="" placeholder="单位数"></div>';
            $('#add_product').click(function() {
                if ($('#products div').length < 14)
                    $('#products').append(input);
            });
            $('#minus_product').click(function() {
                if ($('#products div').length > 2) {
                    $('#products div').last().remove();
                    $('#products div').last().remove();
                }
            });
            @if ($box->express == 0)
            $('#save').click(function(){
                return confirm("已有库存数据将被清零，确定要保存么？");
            });
            @endif
        });
    </script>

@endsection