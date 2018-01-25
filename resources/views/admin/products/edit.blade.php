@extends('layouts.app')

@section('title', '编辑商品')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>编辑商品</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('admin.products.update', $product->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">商品名称</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="name" name="name" value="{{ !empty(old('name')) ? old('name') : $product->name }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="country" class="col-sm-4 control-label">商品国籍</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="country" name="country" value="{{ !empty(old('country')) ? old('country') : $product->country }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-4 control-label">商品定价</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ !empty(old('price')) ? old('price') : $product->price }}" placeholder=".00元">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="life" class="col-sm-4 control-label">保质期</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="life" name="life" value="{{ !empty(old('life')) ? old('life') : $product->life }}" placeholder="天">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type" class="col-sm-4 control-label">销售类型</label>
                        <div class="col-sm-4">
                            <select name="type" id="type" class="form-control">
                                @foreach ($types as $type)
                                <option value="{{ $type }}" {{ $product->type == $type ? 'selected' : '' }}>{{ product_type($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="times" class="col-sm-4 control-label">配送次数</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="times" name="times" value="{{ !empty(old('times')) ? old('times') : $product->times }}" placeholder="次">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="length" class="col-sm-4 control-label">长度</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="length" name="length" value="{{ !empty(old('length')) ? old('length') : $product->length }}" placeholder="mm">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="width" class="col-sm-4 control-label">宽度</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="width" name="width" value="{{ !empty(old('width')) ? old('width') : $product->width }}" placeholder="mm">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="height" class="col-sm-4 control-label">高度</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="height" name="height" value="{{ !empty(old('height')) ? old('height') : $product->height }}" placeholder="mm">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="weight" class="col-sm-4 control-label">重量</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="weight" name="weight" value="{{ !empty(old('weight')) ? old('weight') : $product->weight }}" placeholder="g">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sweetness" class="col-sm-4 control-label">甜咸</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="sweetness" name="sweetness" value="{{ !empty(old('sweetness')) ? old('sweetness') : $product->sweetness }}" placeholder="咸:-3 ~ 甜:3">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hardness" class="col-sm-4 control-label">软硬</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="hardness" name="hardness" value="{{ !empty(old('hardness')) ? old('hardness') : $product->hardness }}" placeholder="软:-3 ~ 硬:3">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="article_id" class="col-sm-4 control-label">关联文章</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="article_id" name="article_id" value="{{ !empty(old('article_id')) ? old('article_id') : $product->article_id }}" placeholder="数字ID">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image_id" class="col-sm-4 control-label">关联图片</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="image_id" name="image_id" value="{{ !empty(old('image_id')) ? old('image_id') : $product->image_id }}"  placeholder="图片ID">
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