<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>续订红包</title>
    <link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://static.openit.shop/bootstrap-theme-20170503.css" rel="stylesheet">
    <style>
        .bg { width: 720px; height: auto; }
        .qrcode { margin-top: -444px; }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xs-12 text-center">
            <img class="bg" src="http://static.openit.shop/coupon_v1.png">
        </div>
        <div class="col-xs-12 text-center">
            <img class="qrcode" src="{{ $qrcode }}">
        </div>
    </div>
</div>
</body>
</html>
