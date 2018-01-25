@extends('layouts.app')

@section('title', $product->displayName)

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <ul class="list-group info">
                    <li class="list-group-item">
                        <div class="col-sm-3">
                            <img src="{{ $product->thumbnail }}" class="img-responsive img-thumbnail">
                        </div>
                        <div class="col-sm-7">
                            <h3>
                                商品：{{ $product->displayName }}
                            </h3>
                            <div>{{ $product->article->subtitle }}</div>
                        </div>
                        <div class="clearfix"></div>
                        <hr>
                        <div class="col-sm-6">
                            <h5>定价：¥{{ $product->price }}</h5>
                        </div>
                        <div class="col-sm-6">
                            <h5>保质期：{{ shelf_life($product->life) }}</h5>
                        </div>
                        <div class="col-sm-6">
                            <h5>规格：{{ $product->length }}x{{ $product->width }}x{{ $product->height }}mm</h5>
                        </div>
                        <div class="col-sm-6">
                            <h5>重量：{{ $product->weight }}g</h5>
                        </div>
                        <div class="col-sm-6">
                            <h5>甜咸：{{ sweetness($product->sweetness) }}</h5>
                        </div>
                        <div class="col-sm-6">
                            <h5>软硬：{{ hardness($product->hardness) }}</h5>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                </ul>
                <img src="{{ $product->article->img_md_wide() }}" class="img-responsive">
                <blockquote>
                    <h2>{{ $product->article->title }}</h2>
                </blockquote>
                <div class="body">{!! $product->article->body !!}</div>
            </div>

            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">操作</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <a class="btn btn-primary btn-block" href="{{ route('admin.products.edit', $product->id) }}">编辑商品</a>
                        </div>
                        @if ($product->deleted_at)
                            <form role="form" method="POST" action="{{ route('admin.products.restore', $product->id) }}" class="public form-group">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-block">公开商品</button>
                            </form>
                        @else
                            <form role="form" method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="private form-group">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-danger btn-block">私有商品</button>
                            </form>
                        @endif
                        <a class="btn btn-success btn-block" href="{{ route('admin.products.index') }}">返回上一级</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(".private").on("submit", function(){
            return confirm("确定要私有这个商品么？");
        });
        $(".public").on("submit", function(){
            return confirm("确定要公开这个商品么？");
        });
    </script>
@endsection