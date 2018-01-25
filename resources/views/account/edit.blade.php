@extends('layouts.address')

@section('title', '会员中心')

@section('content-with-modal')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>会员中心</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('account.update') }}">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    @if (!empty($user->avatar))
                        <div class="form-group">
                            <label for="avatar" class="col-sm-3 control-label">头像</label>
                            <div class="col-sm-6">
                                <img src="{{ $user->avatar }}" width="90" class="img-responsive img-circle">
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">账号</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="email" name="email" value="{{ $user->email }}" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">昵称</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address_id" class="col-sm-3 control-label">收货地址</label>
                        <div class="col-sm-6">
                            <ul id="address-box" class="list-group {{ empty($user->address) ? 'hide' : '' }}">
                                <li class="list-group-item">
                                    <div id="active-contact" class="active-contact">
                                        {{ empty($user->address) ? '' : $user->address->contact_string }}
                                    </div>
                                    <div id="active-address" class="active-address">
                                        {{ empty($user->address) ? '' : $user->address->address_string }}
                                    </div>
                                </li>
                            </ul>
                            <button type="button" class="btn btn-default btn-block" data-toggle="modal" data-target="#address-modal">{{ empty($user->address) ? '新增地址' : '切换地址' }}</button>
                            <input type="hidden" id="address_id" name="address_id" value="{{ empty($user->address) ? 0 : $user->address->id }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <button type="submit" class="btn btn-warning btn-block"><h4>保存</h4></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
