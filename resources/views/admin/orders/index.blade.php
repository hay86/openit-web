@extends('layouts.app')

@section('title', '所有订单')

@section('stylesheets')

    <style>
        .orders .cancel { display: inline; }
        .orders .confirm { display: inline; }
        .orders .refund { display: inline; }
        .orders .deliver { display: inline; }
        .orders .delayed { display: inline; }
        .orders .red {color: red; }
        .orders .contact { vertical-align: middle; }
        .orders .box { width: 100px; }
    </style>

@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>订单共 {{ $orders->total() }} 个</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-primary">
                    <div class="panel-heading">订单列表</div>
                    <table class="table table-condensed table-hover orders">
                        <thead>
                        <tr>
                            <th>订单号</th>
                            <th>用户</th>
                            <th>金额</th>
                            <th>送达</th>
                            <th>待配送</th>
                            <th>上次配送</th>
                            <th>订单状态</th>
                            <th>创建于</th>
                            <th>修改于</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <th>
                                    {{ $order->id }}
                                    @if ($order->notes)
                                        <span class="glyphicon glyphicon-flag red" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ $order->notes }}"></span>
                                    @endif
                                </th>
                                <td><a href="{{ route('admin.orders.index', ['user_id' => $order->user_id]) }}" class="btn btn-link btn-xs">{{ $order->user->name }}</a></td>
                                <td>¥{{ $order->total_fee }}</td>
                                <td>{{ week_day($order->prefer_day) }}</td>
                                <td>{{ $order->balance }}次</td>
                                <td>
                                    @if (isset($order->express))
                                        <a href="{{ route('admin.boxes.show', $order->express->box_id) }}" class="btn btn-link btn-xs">{{ $order->express->box_id }}号盒</a>
                                    @endif
                                </td>
                                <td>{{ order_status($order->status) }}</td>
                                <td>{{ $order->created_at->diffForHumans() }}</td>
                                <td>{{ $order->updated_at->diffForHumans() }}</td>
                                <td class="text-right">
                                    @if ($order->status == \App\Order::SUBMITTED)
                                    <form role="form" method="POST" class="confirm" action="{{ route('admin.orders.update', ['id' => $order->id, 'status' => \App\Order::CONFIRMED]) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}
                                        <button type="submit" class="btn btn-success btn-sm">确认</button>
                                    </form>
                                    <form role="form" method="POST" class="cancel" action="{{ route('admin.orders.update', ['id' => $order->id, 'status' => \App\Order::CANCELED]) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}
                                        <button type="submit" class="btn btn-danger btn-sm">取消</button>
                                    </form>
                                    @elseif ($order->status == \App\Order::CONFIRMED)
                                    <form role="form" method="POST" class="cancel" action="{{ route('admin.orders.update', ['id' => $order->id, 'status' => \App\Order::CANCELED]) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}
                                        <button type="submit" class="btn btn-danger btn-sm">取消</button>
                                    </form>
                                    @elseif ($order->status == \App\Order::SERVING)
                                    <form role="form" method="POST" class="refund" action="{{ route('admin.orders.update', ['id' => $order->id, 'status' => \App\Order::REFUND]) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}
                                        <button type="submit" class="btn btn-danger btn-sm" value="¥{{ $order->refund }}">退款</button>
                                    </form>
                                    @elseif ($order->status == \App\Order::PICK_UP)
                                    <form role="form" method="POST" class="deliver" action="{{ route('admin.orders.update', ['id' => $order->id, 'status' => \App\Order::DELIVERED]) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}
                                        <button type="submit" class="btn btn-success btn-sm">发货</button>
                                    </form>
                                    @elseif ($order->status == \App\Order::DELIVERED)
                                    <form role="form" method="POST" class="delayed" action="{{ route('admin.orders.notification', ['id' => $order->id, 'status' => \App\Express::DELAYED]) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}
                                        <button type="submit" class="btn btn-danger btn-sm">延误短信</button>
                                    </form>
                                    <a href="{{ route('order.express', $order->id) }}" class="btn btn-success btn-sm">物流</a>
                                    @endif
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-primary btn-sm">查看</a>
                                </td>
                            </tr>
                            @if ($order->status == \App\Order::PACKING)
                            <tr class="success">
                                <td colspan="2" class="contact">
                                    {{ $order->address->contact_string }}
                                </td>
                                <td colspan="8" class="text-right">
                                    <form class="form-inline packing" role="form" method="POST" action="{{ route('admin.orders.update', ['id' => $order->id, 'status' => \App\Order::PICK_UP]) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}
                                        <select name="courier_firm" id="courier_firm" class="form-control">
                                            <option value="" disabled>快递公司</option>
                                            @foreach ($courier_firms as $firm)
                                                <option value="{{ $firm }}">{{ courier_firm($firm)['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-group">
                                            <label class="sr-only" for="courier_num">快递号</label>
                                            <input type="text" class="form-control" id="courier_num" name="courier_num" placeholder="快递号">
                                        </div>
                                        <div class="form-group">
                                            <label class="sr-only" for="box_id">库存号</label>
                                            <input type="number" class="form-control box" id="box_id" name="box_id" placeholder="库存号">
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm">出库</button>
                                    </form>
                                </td>
                            </tr>
                            @elseif ($order->status == \App\Order::PICK_UP)
                            <tr class="success">
                                <td colspan="2" class="contact">
                                    {{ $order->express->address->contact_string }}
                                </td>
                                <td colspan="8" class="text-right">
                                    <form class="form-inline packing" role="form" method="POST" action="{{ route('admin.orders.update', ['id' => $order->id, 'status' => \App\Order::PICK_UP]) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}
                                        <select name="courier_firm" id="courier_firm" class="form-control">
                                            <option value="" disabled>快递公司</option>
                                            @foreach ($courier_firms as $firm)
                                                <option value="{{ $firm }}" {{ $order->express->courier_firm == $firm ? 'selected' : '' }}>{{ courier_firm($firm)['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-group">
                                            <label class="sr-only" for="courier_num">快递号</label>
                                            <input type="text" class="form-control" id="courier_num" name="courier_num" placeholder="快递号" value="{{ $order->express->courier_num }}">
                                        </div>
                                        <div class="form-group">
                                            <label class="sr-only" for="box_id">库存号</label>
                                            <input type="number" class="form-control box" id="box_id" name="box_id" placeholder="库存号" value="{{ $order->express->box_id }}">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm">保存</button>
                                        <a href="{{ route('admin.orders.print', $order->id) }}" class="btn btn-success btn-sm" target="_blank">发货清单</a>
                                        @if ($order->next_express_no >= $order->product->times)
                                        <a href="{{ route('admin.orders.coupon', $order->id) }}" class="btn btn-warning btn-sm" target="_blank">续订红包</a>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">操作</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="GET" action="{{ route('admin.orders.index') }}">
                            <div class="form-group">
                                <label for="prefer_day" class="col-sm-4 control-label">送达</label>
                                <div class="col-sm-8">
                                    <select name="prefer_day" id="prefer_day" class="form-control">
                                        <option value="">所有日期</option>
                                        @foreach ($prefer_days as $prefer_day)
                                        <option value="{{ $prefer_day }}" {{ isset($request->prefer_day) && $request->prefer_day == $prefer_day ? 'selected' : '' }}>{{ week_day($prefer_day) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status" class="col-sm-4 control-label">状态</label>
                                <div class="col-sm-8">
                                    <select name="status" id="status" class="form-control">
                                        <option value="">所有状态</option>
                                        @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ isset($request->status) && $request->status == $status ? 'selected' : '' }}>{{ order_status($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="user_id" class="col-sm-4 control-label">用户</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="user_id" name="user_id" value="{{ isset($request->user_id) ? $request->user_id : '' }}" placeholder="数字ID">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="referer_id" class="col-sm-4 control-label">邀请码</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="referer_id" name="referer_id" value="{{ isset($request->referer_id) ? $request->referer_id : '' }}" placeholder="数字ID">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-block">查询订单</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button id="copy" type="button" class="btn btn-success btn-block">复制地址</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    {!! $orders->appends($request->all())->links() !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <textarea id="address_lines" class="hide">
@foreach ($orders as $order)
{{ $order->address->username }}，{{ $order->address->mobile }}，{{ $order->address->address_string }}；
@endforeach
                </textarea>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        $(function () {
            $(".cancel").on("submit", function(){
                return confirm("确定要取消这个订单么？");
            });
            $(".confirm").on("submit", function(){
                return confirm("确定要确认这个订单么？");
            });
            $(".refund").on("submit", function(){
                var rate = "{{ \App\Order::REFUND_FEE_RATE }}";
                var refund = $(this).find(':submit').val();
                return confirm("收取" + rate + "%手续费，实际退款" + refund + "，确定要退款这个订单么？");
            });
            $(".deliver").on("submit", function(){
                return confirm("确定要发货么？");
            });
            $(".delayed").on("submit", function(){
                return confirm("确定要发送快递延误通知么？");
            });
            $(".packing").on("submit", function(){
                var firm = $(this).find('#courier_firm option:selected').html();
                var num = $(this).find('#courier_num').val();
                var box_id = $(this).find('#box_id').val();
                return confirm("【" + firm + "】" + num + "，确定【" + box_id + "号盒】要出库么？");
            });
            $('[data-toggle="tooltip"]').tooltip()
            $('#copy').click(function(){
                $("#address_lines").removeClass('hide');
                $("#address_lines").select();
                document.execCommand('copy');
                $("#address_lines").addClass('hide');
                alert('复制成功！');
            });
        })
    </script>

@endsection