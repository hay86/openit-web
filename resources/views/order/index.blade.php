@extends('layouts.app')

@section('active_order_index', 'active')

@section('title', '我的订单')

@section('stylesheets')

    <style>
        .order .title {
            font-size: 16px;
            color: #333;
        }
        .order .subtitle {
            font-size: 12px;
            padding-top: 5px;
            padding-bottom: 10px;
            color: #bbb;
        }
        .order .text-sm {
            font-size: 12px;
        }
        .order .info {
            margin-bottom: 15px;
        }
    </style>

@endsection

@section('content')

    <div class="container order">
        @if (count($orders) == 0)
            <div class="row">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <h1><small>没有订单 ╮(╯_╰)╭</small></h1>
                    <a href="{{ route('order.create') }}" class="btn btn-primary btn-block">我要下单</a>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    @foreach ($orders as $order)
                        <div class="form-group">
                            <label class="control-label">订单 #{{ ($orders->currentPage()-1) * $orders->perPage() + $loop->iteration }}</label>
                            <ul class="list-group info">
                                <li class="list-group-item">
                                    <div class="title">{{ $order->product->displayName }}@if(!empty($order->notes))（{{ $order->notes }}）@endif</div>
                                    <div class="subtitle">您订了{{ duration($order->product->times) }}，每{{ week_day($order->prefer_day) }}送达，实付¥{{ $order->cash_fee }}</div>
                                    <div class="text-sm">订单编号：{{ $order->id }}</div>
                                    <div class="text-sm">下单时间：{{ $order->created_at }}</div>
                                    @if (!empty($order->trade_time))
                                        <div class="text-sm">支付时间：{{ $order->trade_time }}</div>
                                    @endif
                                    <div class="text-sm">当前状态：{{ order_status($order->status) }}</div>
                                </li>
                            </ul>
                            <div class="text-right">
                                @if ($order->status == \App\Order::CONFIRMED && !$order->trade_time)
                                    <a href="{{ route('order.show', $order->id) }}" class="btn btn-warning btn-sm">马上付款</a>
                                @else
                                    <a href="{{ route('order.show', $order->id) }}" class="btn btn-default btn-sm">查看订单</a>
                                    <a href="{{ route('order.express', $order->id) }}" class="btn btn-default btn-sm">查看物流</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        {!! $orders->links() !!}
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection