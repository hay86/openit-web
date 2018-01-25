<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- SEO -->
    <meta name="description" content="「Openit」是一种新颖的零食订购服务，旨在为广大美食星球的人类解决零食选购的难题。「Openit」是目前国内仅有的几家针对个人提供零食订购服务的公司之一，于2017年3月在上海成立，由一名爱吃零食的互联网程序员——“零先生”创办，并邀请海归高知馋嘴猫朋友——“壹小姐”共同合作。" />
    <meta name="keywords" content="Openit,零食,美食,吃货,馋嘴,零先生" />
    <meta name="author" content="零先生" />
    <meta name="robots" content="index,follow" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- favicon -->
    <link rel="shortcut icon" href="http://static.openit.shop/favicon.ico">
    <link rel="icon" type="image/png" href="http://static.openit.shop/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="http://static.openit.shop/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="http://static.openit.shop/favicon-16x16.png" sizes="16x16">
    <link rel="apple-touch-icon" sizes="57x57" href="http://static.openit.shop/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="http://static.openit.shop/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="http://static.openit.shop/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="http://static.openit.shop/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="http://static.openit.shop/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="http://static.openit.shop/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="http://static.openit.shop/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="http://static.openit.shop/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="http://static.openit.shop/apple-touch-icon-180x180.png">
    <meta name="apple-mobile-web-app-title" content="Openit">
    <link rel="manifest" href="http://static.openit.shop/manifest.json">
    <link rel="icon" type="image/png" href="http://static.openit.shop/android-chrome-192x192.png" sizes="192x192">
    <meta name="application-name" content="Openit">
    <meta name="msapplication-TileColor" content="#b20000">
    <meta name="msapplication-TileImage" content="http://static.openit.shop/mstile-144x144.png">
    <meta name="msapplication-config" content="http://static.openit.shop/browserconfig.xml" />
    <link rel="mask-icon" href="http://static.openit.shop/safari-pinned-tab.svg" color="color">
    <meta name="theme-color" content="#b20000">

    <title>@yield('title') | {{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://static.openit.shop/bootstrap-theme-20170503.css" rel="stylesheet">
    @include('layouts.css')
    @yield('stylesheets')

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?e2de61c7783de466406f46420c0582bf";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</head>
<body>
    <div id="app">
        @include('layouts.nav')
        @include('layouts.message')
        @yield('content')
        @include('layouts.footer')
    </div>

    <!-- Scripts -->
    <script src="http://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="http://cdn.bootcss.com/jquery.touchswipe/1.6.18/jquery.touchSwipe.min.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    @include('layouts.js')
    @yield('scripts')
</body>
</html>
