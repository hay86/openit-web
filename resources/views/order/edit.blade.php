@extends('layouts.address')

@section('title', '修改订单')

@section('stylesheets-with-modal')

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
        .order .option {
            display: inline;
            float: left;
            margin-right: 8px;
            margin-bottom: 8px;
        }
        .order .clear-option {
            clear: both;
        }
        .order .red {
            color: red;
        }
        .order .info {
            margin-bottom: 15px;
        }
    </style>

@endsection

@section('content-with-modal')

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
                <form role="form" method="POST" action="{{ route('order.update', $order->id) }}">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-group">
                        <label class="control-label">订单信息</label>
                        <ul class="list-group info">
                            <li class="list-group-item">
                                <div class="title">{{ $order->product->displayName }}@if(!empty($order->notes))（{{ $order->notes }}）@endif</div>
                                <div class="subtitle">您订了{{ duration($order->product->times) }}，每{{ week_day($order->prefer_day) }}送达，实付¥{{ $order->product->price }}</div>
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
                                <div id="not_change" class="title">
                                    {{ $order->next_express > 0 ? date('n月j日', $order->next_express) : '配送结束' }}
                                </div>
                                <div id="change_to" class="title red hidden"></div>
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
                        <label for="prefer_day" class="control-label">送达时间</label>
                        <div id="prefer_days">
                            @foreach ($prefer_days as $id)
                                <div class="list-group option">
                                    <a id="{{ $id }}" href="javascript:void(0);" class="list-group-item {{ $id == $order->prefer_day ? 'active' : '' }}" onclick="day_click(this)">{{ week_day($id) }}</a>
                                </div>
                            @endforeach
                            <div class="clear-option"></div>
                        </div>
                        <input type="hidden" id="prefer_day" name="prefer_day" value="{{ $order->prefer_day }}">
                    </div>
                    <div class="form-group">
                        <label for="delay_weeks" class="control-label">延期配送</label>
                        <div id="delay_weeks">
                            @if ($order->status == \App\Order::DELAYED)
                                <div class="list-group option">
                                    <a id="0" href="javascript:void(0);" class="list-group-item" onclick="week_click(this)">不延期</a>
                                </div>
                                <div class="list-group option">
                                    <a id="{{ strtotime($order->express_since) }}" href="javascript:void(0);" class="list-group-item active" onclick="week_click(this)">延至{{  date('n月j日', strtotime($order->express_since)) }}后</a>
                                </div>
                            @else
                                <div class="list-group option">
                                    <a id="0" href="javascript:void(0);" class="list-group-item active" onclick="week_click(this)">不延期</a>
                                </div>
                                @foreach ($delay_weeks as $id)
                                <div class="list-group option">
                                    <a id="{{ strtotime('Today') + 3600*24*7*$id }}" href="javascript:void(0);" class="list-group-item" onclick="week_click(this)">延期{{ $id }}周</a>
                                </div>
                                @endforeach
                            @endif
                            <div class="clear-option"></div>
                        </div>
                        <input type="hidden" id="delay_week" name="delay_week" value="{{ $order->status == \App\Order::DELAYED ? strtotime($order->express_since) : 0 }}">
                    </div>
                    <div class="form-group">
                        <label for="address_id" class="control-label">收货地址</label>
                        <ul id="address-box" class="list-group info">
                            <li class="list-group-item">
                                <div id="active-contact" class="active-contact">
                                    {{ $order->address->contact_string }}
                                </div>
                                <div id="active-address" class="active-address">
                                    {{ $order->address->address_string }}
                                </div>
                            </li>
                        </ul>
                        <button type="button" class="btn btn-default btn-block" data-toggle="modal" data-target="#address-modal">切换地址</button>
                        <input type="hidden" id="address_id" name="address_id" value="{{ $order->address->id }}">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-warning btn-block"><h4>保存</h4></button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

@endsection

@section('scripts-with-modal')

    <script>
        var exp = {};
        @foreach ($prefer_days as $i)
            exp[{{ $i }}] = {};
            @foreach ($delay_weeks as $j)
                exp[{{ $i }}][{{ strtotime('Today') + 3600*24*7*$j }}] = "{{ date('n月j日', express_date($i, strtotime('Today') + 3600*24*7*$j)) }}";
            @endforeach
        @endforeach
        @if ($order->status == \App\Order::DELAYED)
            @foreach ($prefer_days as $i)
                exp[{{ $i }}][0] = "{{ date('n月j日', express_date($i)) }}";
                exp[{{ $i }}][{{ strtotime($order->express_since) }}] = "{{ $i == $order->prefer_day ? '' : date('n月j日', express_date($i, strtotime($order->express_since))) }}";
            @endforeach
        @else
            @foreach ($prefer_days as $i)
                exp[{{ $i }}][0] = "{{ $i == $order->prefer_day ? '' : date('n月j日', express_date($i)) }}";
            @endforeach
        @endif

        function day_click(e) {
            $('#prefer_days a').removeClass('active');
            var elem = $(e);
            elem.addClass('active');
            $('#prefer_day').val(elem.attr('id'));
            change_express();
        }

        function week_click(e) {
            $('#delay_weeks a').removeClass('active');
            var elem = $(e);
            elem.addClass('active');
            $('#delay_week').val(elem.attr('id'));
            change_express();
        }

        function change_express() {
            var next_express = exp[$('#prefer_day').val()][$('#delay_week').val()];
            $('#change_to').html(next_express);
            if (next_express == '' || $.trim($('#change_to').html()) == $.trim($('#not_change').html())) {
                $('#not_change').removeClass('hidden');
                $('#change_to').addClass('hidden');
            }
            else {
                $('#not_change').addClass('hidden');
                $('#change_to').removeClass('hidden');
            }
        }
    </script>

@endsection