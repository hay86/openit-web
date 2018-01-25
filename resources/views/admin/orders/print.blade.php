<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>发货清单</title>
    <link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://static.openit.shop/bootstrap-theme-20170503.css" rel="stylesheet">
    <style>
        @media print {
            .red {
                color: #b20000 !important;
            }
        }
        body {
            padding: 40px;
        }
        table {
            margin-top: 40px;
        }
        table img {
            height: 100px;
        }
        table tr.noborder td {
            border: none;
        }
        table tr td.padtop {
            padding-top: 30px;
        }
        table tr td.padbot {
            padding-bottom: 30px;
        }
        .title {
            font-size: 28px;
            color: #b20000;
        }
        .logo {
            font-family: "Disney";
            font-size: 42px;
        }
        .column {
            width: 80px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-6">
            <div class="title red"><span class="logo red">Openit</span> 零食</div>
            <div>生活太单调？快来Openit！</div>
            <div>每周只需一杯咖啡的钱，进口零食免费送到家！</div>
        </div>
        <div class="col-xs-6">
            <div>收货人：{{ $order->address->username }}</div>
            <div>订单编号：{{ $order->id }}</div>
            <div>下单时间：{{ $order->created_at }}</div>
            <div>支付时间：{{ $order->trade_time }}</div>
            <div>收货地址：{{ $order->address->address_string }}</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <table class="table">
                <tr>
                    <td colspan="4" class="text-center">
                        {{ $order->product->displayName }} (第 {{ $order->next_express_no }} 盒 / 共 {{ count($order->express_dates) }} 盒)
                    </td>
                </tr>
                @if ($box)
                @for ($i=0; $i<count($box->products); $i+=2)
                <tr>
                    <td class="padtop column">商品编号</td>
                    <td class="padtop">{{ sprintf('%04d', $box->products[$i]->id) }}</td>
                    @if ($i<count($box->products))
                        <td class="padtop column">商品编号</td>
                        <td class="padtop">{{ sprintf('%04d', $box->products[$i+1]->id) }}</td>
                    @endif
                </tr>
                <tr class="noborder">
                    <td class="column">商品图</td>
                    <td><img src="{{ $box->products[$i]->thumbnail }}"></td>
                    @if ($i<count($box->products))
                        <td class="column">商品图</td>
                        <td><img src="{{ $box->products[$i+1]->thumbnail }}"></td>
                    @endif
                </tr>
                <tr class="noborder">
                    <td class="column">商品名</td>
                    <td>{{ $box->products[$i]->displayName }}</td>
                    @if ($i<count($box->products))
                        <td class=" column">商品名</td>
                        <td>{{ $box->products[$i+1]->displayName }}</td>
                    @endif
                </tr>
                <tr class="noborder">
                    <td class="column">推荐理由</td>
                    <td>{{ $box->products[$i]->article->subtitle }}</td>
                    @if ($i<count($box->products))
                        <td class="column">推荐理由</td>
                        <td>{{ $box->products[$i+1]->article->subtitle }}</td>
                    @endif
                </tr>
                <tr class="noborder">
                    <td class="padbot column">微信点评</td>
                    <td class="padbot"><img src="{{ $qrcodes[$i] }}"></td>
                    @if ($i<count($box->products))
                        <td class="padbot column">微信点评</td>
                        <td class="padbot"><img src="{{ $qrcodes[$i+1] }}"></td>
                    @endif
                </tr>
                @endfor
                @endif
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-center">
            <div>— 让零食温暖你的心和胃 —</div>
            <div>Openit 团队</div>
        </div>
    </div>
</div>
</body>
</html>
