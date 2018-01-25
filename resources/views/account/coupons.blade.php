@extends('layouts.app')

@section('active_account_coupons', 'active')

@section('title', '我的优惠券')

@section('stylesheets')

<style>
    .main { height:120px; padding:10px; margin:6px 0; background-color:#f0ad4e; color:#fff; }
    .vice { height:120px; padding:10px; margin:6px 0; background-color:#f5f5f5; color:#555; }
    .text-num { font-size:52px; }
    .text-sm { font-size:12px; }
    .buttons { margin-top:35px; }
    .main:before { content:""; position:absolute; display:block; width:10px; height:100%; left:-10px; top:0; }
    .main:before {
        background-color:#fff;
        background-size:20px 10px;
        background-position:100% 35%;
        background-image:linear-gradient(-45deg, #f0ad4e 25%, transparent 25%, transparent), linear-gradient(-135deg, #f0ad4e 25%, transparent 25%, transparent), linear-gradient(-45deg, transparent 75%, #f0ad4e 75%), linear-gradient(-135deg, transparent 75%, #f0ad4e 75%);
    }
</style>

@endsection

@section('content')

<div class="container">
    @if (count($coupons) == 0)
        <div class="row">
            <div class="col-md-6 col-md-offset-3 text-center">
                <h1><small>没有优惠券 ╮(╯_╰)╭</small></h1>
            </div>
        </div>
    @else
        @foreach ($coupons as $coupon)
        <div class="row">
            <div class="col-md-6 col-md-offset-3 col-xs-12">
                <div class="col-xs-5 main">
                    <div class="text-sm">全场通用</div>
                    <div class="text-sm text-center">¥<span class="text-num">{{ $coupon->discount }}</span>优惠券</div>
                </div>
                <div class="col-xs-7 vice">
                    <div class="text-sm">有效期</div>
                    <div class="text-sm">{{ date('Y-m-d', $coupon->created_at->timestamp) }} - {{ date('Y-m-d', strtotime($coupon->expired_at)) }}</div>
                    <div class="text-right buttons">
                        <a class="btn btn btn-sm btn-warning" href="{{ route('order.create') }}">立即使用</a>
                        <a class="btn btn btn-sm btn-warning" href="{{ route('account.referer') }}">赠送朋友</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>

@endsection