@extends('layouts.app')

@section('title', '微信支付')

@section('stylesheets')

    <style>
        .text-above { margin-top: 40px; margin-bottom: 20px; }
        .text-below { margin-top: 10px; }
        .glyphicon.spinning {
            animation: spin 1s infinite linear;
            -webkit-animation: spin2 1s infinite linear;
        }

        @keyframes spin {
            from { transform: scale(1) rotate(0deg); }
            to { transform: scale(1) rotate(360deg); }
        }

        @-webkit-keyframes spin2 {
            from { -webkit-transform: rotate(0deg); }
            to { -webkit-transform: rotate(360deg); }
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
                <div class="col-md-6 col-md-offset-3 text-center">
                    <div id="waiting">
                    @if (empty($qrcode))
                        <h1>
                            <small>
                                <span class="glyphicon glyphicon-repeat spinning" aria-hidden="true"></span>
                                <span>等待确认</span>
                            </small>
                        </h1>
                    @else
                        <p class="text-above lead">微信扫一扫 3秒极速支付</p>
                        <img src="{{ $qrcode }}" alt="使用微信扫码支付" class="img-thumbnail img-responsive">
                        <h4 class="text-below"><span id="msg" class="label label-success">使用微信扫码支付</span></h4>
                    @endif
                    </div>
                    <div id="success" class="hidden">
                        <h1>
                            <small>
                                <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
                                <span>支付成功</span>
                            </small>
                        </h1>
                        <div>
                            <p class="text-above lead">关注官方微信 跟踪订单动态</p>
                            <p><img src="http://static.openit.shop/qrcode_258.jpg" alt="微信搜索公众号「Openit」" width="150"></p>
                            <h4 class="text-below"><span id="msg" class="label label-success">使用微信扫码关注</span></h4>
                        </div>
                    </div>
                    <div id="failed" class="hidden">
                        <h1>
                            <small>
                                <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
                                <span>支付失败</span>
                            </small>
                        </h1>
                        <div>
                            <p class="text-above lead">
                                原因：<span id="reason"></span>
                            </p>
                        </div>
                    </div>
                    <div id="timeout" class="hidden">
                        <h1>
                            <small>
                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                <span>支付超时</span>
                            </small>
                        </h1>
                        <div>
                            <p class="text-above lead">
                                请返回<a href="{{ route('order.show', $order->id) }}">订单页面</a>重新支付
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

@section('scripts')

    @if (!empty($order))
    <script>

        var interval = 3000;
        var times = {{ $expire*1000 }} / interval;

        $(function(){
            ajax_check();
        });
        function ajax_check() {
            $.ajax({
                url             : "{{ route('payment.check', $order->id) }}",
                headers         : { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                type            : "POST",
                cache           : false,
                contentType     : false,
                processData     : false,
                success         : function( r ){
                    if (typeof r.paid != 'undefined') {
                        $('#waiting').addClass('hidden');
                        if (r.paid) {
                            $('#success').removeClass('hidden');
                        }
                        else {
                            $('#reason').html(r.reason);
                            $('#failed').removeClass('hidden');
                        }
                    }
                    else if (-- times > 0) {
                        setTimeout(ajax_check, interval);
                    }
                    else {
                        $('#waiting').addClass('hidden');
                        $('#timeout').removeClass('hidden');
                    }
                }
            }, 'json');
        }
    </script>
    @endif

@endsection