@extends('layouts.app')

@section('title', '创建优惠券')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>{{ !empty($user) ? '给'.$user->name : '' }}创建优惠券</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('admin.coupons.store') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="discount" class="col-sm-4 control-label">折扣</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="discount" name="discount" value="{{ old('discount') }}" placeholder="1-50元">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="expired_in" class="col-sm-4 control-label">过期时间</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="expired_in" name="expired_in" value="{{ old('expired_in') }}" placeholder="1-12个月">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user_id" class="col-sm-4 control-label">关联用户</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="user_id" name="user_id" value="{{ !empty(old('user_id')) ? old('user_id') : (!empty($user) ? $user->id : '') }}" placeholder="数字ID">
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