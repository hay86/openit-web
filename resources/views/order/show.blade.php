@extends('layouts.app')

@section('title', '查看订单')

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
        .order .text-sm-bottom {
            font-size: 12px;
            padding-top: 6px;
        }
        .order .price {
            font-size: 14px;
            padding-top: 8px;
            color: red;
        }
        .order .price .big {
            font-size: 20px;
        }
        .order .info {
            margin-bottom: 15px;
        }
        .order .error {
            text-align: center;
        }
    </style>

@endsection

@section('content')

    <div class="container order">
        @if (empty($order))
        <div class="row">
            <div class="col-md-6 col-md-offset-3 text-center">
                <h1><small>没有订单 ╮(╯_╰)╭</small></h1>
                <a href="{{ route('order.create') }}" class="btn btn-primary btn-block">我要下单</a>
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="form-group">
                    <label class="control-label">订单信息</label>
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
                </div>
                <div class="form-group">
                    <label class="control-label">下次配送</label>
                    <ul class="list-group info">
                        <li class="list-group-item">
                            <div class="title">
                                {{ $order->next_express > 0 ? date('n月j日', $order->next_express) : '配送结束' }}
                            </div>
                            <div class="text-sm-bottom">
                                @if ($order->next_express > 0)
                                    第{{ $order->next_express_no }}次配，共配送{{ $order->product->times }}次
                                @else
                                    合计已配送{{ count($order->expresses) }}次
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="form-group">
                    <label class="control-label">收货地址</label>
                    <ul class="list-group info">
                        <li class="list-group-item">
                            <div class="contact">
                                {{ $order->address->contact_string }}
                            </div>
                            <div class="address">
                                {{ $order->address->address_string }}
                            </div>
                        </li>
                    </ul>
                    @if ($order->next_express > 0)
                    <a href="{{ route('order.edit', $order->id) }}" class="btn btn-default btn-block">修改收货时间地址</a>
                    @endif
                </div>
                @if ($order->status == \App\Order::CONFIRMED && !$order->trade_time)
                <hr>
                <div class="form-group text-right">
                    <div>商品金额：<span class="price">¥{{ $order->product->price }}</span></div>
                    <div>+ 运费：<span class="price">¥{{ $order->express_fee }}</span></div>
                    @if ($order->coupon_fee > 0)
                    <div>- 优惠：<span class="price">¥{{ $order->coupon_fee }}</span></div>
                    @endif
                    <div>付款金额：<span class="price">¥<span class="big">{{ $order->cash_fee }}</span></span></div>
                </div>
                <p id="error-msg" class="error"></p>
                <div class="form-group">
                    <button class="btn btn-warning btn-block payment"><h4>确认付款</h4></button>
                </div>
                @endif
            </div>
        </div>
        @endif

        <div class="hidden">
            <form method="POST" action="{{ route('payment.show', $order->id) }}">
                {{ csrf_field() }}
                <input type="text" name="code_url" id="code_url">
                <button type="submit" id="redirect">跳转</button>
            </form>
        </div>
    </div>

@endsection

@section('scripts')

    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script>
        $(function(){
            $('.payment').click(function(){
                $('.payment').prop('disabled', true);
                $.ajax({
                    url             : "{{ route('payment.prepare', $order->id) }}",
                    headers         : { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                    type            : "POST",
                    data            : '',
                    cache           : false,
                    contentType     : false,
                    processData     : false,
                    success         : function( r ){
                        if (r.status == 'failed') {
                            $('#error-msg').html('<code>'+r.reason+'</code>');
                        }
                        else {
                            if (typeof r.config != 'undefined') {
                                // popup wechat payment plugin
                                $('#code_url').val('');
                                $('#error-msg').html('');

                                wx.config({!! $js->config(['chooseWXPay']) !!});
                                wx.chooseWXPay({
                                    timestamp   : r.config['timestamp'],
                                    nonceStr    : r.config['nonceStr'],
                                    package     : r.config['package'],
                                    signType    : r.config['signType'],
                                    paySign     : r.config['paySign'],
                                    success     : function () {
                                        $('#redirect').click();
                                    }
                                });
                            }
                            else if (typeof r.code_url != 'undefined') {
                                // redirect to payment qrcode
                                $('#code_url').val(r.code_url);
                                $('#redirect').click();
                            }
                            else {
                                $('#error-msg').html(STR_TPL_1.format('支付失败，请重试！'));
                            }
                        }
                        $('.payment').prop('disabled', false);
                    },
                    error           : function(){
                        $('#error-msg').html(STR_TPL_1.format('支付失败，请重试！'));
                        $('.payment').prop('disabled', false);
                    }
                }, 'json');
            });
        });
    </script>

@endsection