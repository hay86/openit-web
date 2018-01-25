@extends('layouts.app')

@section('title', '订单 #' . $order->id)

@section('content')

    <div class="container order">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>订单 #{{ $order->id }}</small></h1>
            </div>
        </div>

        <div class="col-md-9">
            <div class="panel panel-primary">
                <div class="panel-heading">订单信息</div>
                <table class="table table-condensed table-hover">
                    <tr>
                        <th>订单号</th>
                        <td>{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <th>商品</th>
                        <td>{{ $order->product->displayName }} / 每{{ week_day($order->prefer_day) }}送达</td>
                    </tr>
                    <tr>
                        <th>用户</th>
                        <td><a href="{{ route('admin.orders.index', ['user_id' => $order->user_id]) }}" class="btn btn-link btn-xs">{{ $order->user->name }}</a></td>
                    </tr>
                    <tr>
                        <th>下单时间</th>
                        <td>{{ $order->created_at }}</td>
                    </tr>
                    <tr>
                        <th>最后修改</th>
                        <td>{{ $order->updated_at }}</td>
                    </tr>
                    <tr>
                        <th>订单状态</th>
                        <td>{{ order_status($order->status) }}</td>
                    </tr>
                </table>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">配送信息</div>
                <table class="table table-condensed table-hover">
                    <tr>
                        <th>联系方式</th>
                        <td>{{ $order->address->contact_string }}</td>
                    </tr>
                    <tr>
                        <th>收货地址</th>
                        <td>{{ $order->address->address_string }}</td>
                    </tr>
                    @foreach ($order->express_dates as $date)
                    <tr>
                        <th>{{ date('n月j日', $date) }}</th>
                        <td>
                            @if ($loop->iteration <= count($order->expresses))
                                <a href="{{ route('admin.boxes.show', $order->expresses[$loop->iteration-1]->box_id) }}" class="btn btn-link btn-xs">{{ $order->expresses[$loop->iteration-1]->box_id }}号盒</a>
                            @else
                                待配送
                            @endif
                        </td>
                    <tr>
                    @endforeach
                </table>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">支付信息</div>
                <table class="table table-condensed table-hover">
                    <tr>
                        <th>总金额</th>
                        <td>¥{{ $order->total_fee }}</td>
                    </tr>
                    <tr>
                        <th>现金额</th>
                        <td>¥{{ $order->cash_fee }}</td>
                    </tr>
                    <tr>
                        <th>快递费</th>
                        <td>¥{{ $order->express_fee }}</td>
                    </tr>
                    <tr>
                        <th>优惠券</th>
                        <td>¥{{ $order->coupon_fee }}</td>
                    </tr>
                    <tr>
                        <th>邀请码</th>
                        <td>{{ $order->referer_id }}</td>
                    </tr>
                    <tr>
                        <th>交易类型</th>
                        <td>{{ $order->trade_type }}</td>
                    </tr>
                    <tr>
                        <th>交易商户</th>
                        <td>{{ $order->trade_mch }}</td>
                    </tr>
                    <tr>
                        <th>交易银行</th>
                        <td>{{ $order->trade_bank }}</td>
                    </tr>
                    <tr>
                        <th>交易时间</th>
                        <td>{{ $order->trade_time }}</td>
                    </tr>
                    <tr>
                        <th>索要发票</th>
                        <td>{{ $order->invoice }}</td>
                    </tr>
                </table>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">订单备注</div>
                <div class="panel-body">
                    <form class="form" role="form" method="POST" action="{{ route('admin.orders.update.notes', $order->id) }}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="input-group">
                            <input type="text" class="form-control" id="notes" name="notes" value="{{ $order->notes }}">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">保存</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">操作</div>
                <div class="panel-body">
                    <div class="form-group">
                        <a href="{{ route('order.express', $order->id) }}" class="btn btn-primary btn-block">查看物流</a>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-success btn-block">返回上一级</a>
                </div>
            </div>
        </div>
    </div>

@endsection