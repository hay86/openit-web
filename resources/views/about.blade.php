@extends('layouts.app')

@section('active_about', 'active')

@section('title', '关于我们')

@section('stylesheets')
    <style>
        .head {
            margin-top: 30px;
            color: #b20000;
        }
        .text {
            margin-top: 1em;
            text-indent: 2em;
        }
        .title {
            margin-top: 15px;
            margin-bottom: 25px;
            font-size: 28px;
            color: #b20000;
        }
        .desc {
            font-size: 14px;
            color: #333;
        }
        .logo {
            font-family: "Disney";
            font-size: 40px;
        }
        .qrcode {
            width: 100%;
            margin-top: 25px;
        }
    </style>
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="body">
                    <h3 class="head">▍品牌介绍</h3>
                    <p class="text">「Openit」是一种新颖的零食订购服务，旨在为广大美食星球的人类解决零食选购的难题。</p>
                    <h3 class="head">▍公司介绍</h3>
                    <p class="text">「Openit」是目前国内仅有的几家针对个人提供零食订购服务的公司之一，于2017年3月在上海成立，由一名爱吃零食的互联网程序员——“零先生”创办，并邀请海归高知馋嘴猫朋友——“壹小姐”共同合作。</p>
                    <h3 class="head">▍品牌故事</h3>
                    <p class="text">程序员出生的零先生除了修bug外，还有一个爱好，就是抱着薯片和奶茶看动画片，但从来不更换薯片和奶茶的口味。持之以恒、坚定执着是零先生的特点。壹小姐在高校任教，眼神锐利、品味独特，她鼓励零先生结合自身的爱好和优势打造一个食品界的电商王国，去实现零先生一直以来的“改变世界”的梦想。性格能力互补、三观一致， 「Openit」就在这样的团队手中诞生了。</p>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <ul class="list-group">
                    <li class="list-group-item">
                        <p class="title"><span class="logo">Openit</span> 零食</p>
                        <p class="desc">生活太单调？快来Openit！</p>
                        <p class="desc">每周只需一杯咖啡的钱，进口零食免费送到家！</p>
                        <p class="desc">你还在等什么？马上关注「Openit」官方微信吧！</p>
                        <div class="qrcode">
                            <p><img src="http://static.openit.shop/qrcode_258.jpg" alt="微信搜索公众号「Openit」" width="150"></p>
                            <h4><span class="label label-success">使用微信扫码关注</span></h4>
                        </div>
                        <br/>
                    </li>
                </ul>
            </div>
        </div>
    </div>

@endsection