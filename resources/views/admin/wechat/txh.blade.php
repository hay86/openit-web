@extends('layouts.app')

@section('title', '管理天下号')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>微信 天下号</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('admin.wechat.txh.update') }}">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-group">
                        <label for="menus" class="col-sm-1 control-label">菜单</label>
                        <div class="col-sm-11">
                            <textarea name="menus" id="menus" class="form-control" rows="25">{{ !empty(old('menus')) ? old('menus') : $menus }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-11">
                            <button type="submit" class="btn btn-primary">保存</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection