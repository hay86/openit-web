@extends('layouts.app')

@section('title', '创建商品')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>创建商品</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('admin.products.store') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">商品名称</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="country" class="col-sm-4 control-label">商品国籍</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="country" name="country" value="{{ old('country') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-4 control-label">商品定价</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price') }}" placeholder=".00元">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="life" class="col-sm-4 control-label">保质期</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="life" name="life" value="{{ old('life') }}" placeholder="天">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type" class="col-sm-4 control-label">销售类型</label>
                        <div class="col-sm-4">
                            <select name="type" id="type" class="form-control">
                                @foreach ($types as $type)
                                <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ product_type($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="times" class="col-sm-4 control-label">配送次数</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="times" name="times" value="{{ old('times') }}" placeholder="次">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="length" class="col-sm-4 control-label">长度</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="length" name="length" value="{{ old('length') }}" placeholder="mm">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="width" class="col-sm-4 control-label">宽度</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="width" name="width" value="{{ old('width') }}" placeholder="mm">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="height" class="col-sm-4 control-label">高度</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="height" name="height" value="{{ old('height') }}" placeholder="mm">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="weight" class="col-sm-4 control-label">重量</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="weight" name="weight" value="{{ old('weight') }}" placeholder="g">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sweetness" class="col-sm-4 control-label">甜咸</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="sweetness" name="sweetness" value="{{ old('sweetness') }}" placeholder="咸:-3 ~ 甜:3">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hardness" class="col-sm-4 control-label">软硬</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="hardness" name="hardness" value="{{ old('hardness') }}" placeholder="软:-3 ~ 硬:3">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="article_id" class="col-sm-4 control-label">关联文章</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="article_id" name="article_id" value="{{ old('article_id') }}" placeholder="数字ID">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image_id" class="col-sm-4 control-label">关联图片</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="image_id" name="image_id" value="{{ old('image_id') }}"  placeholder="图片ID">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-4">
                            <button type="submit" class="btn btn-primary">保存</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection