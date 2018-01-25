@extends('layouts.app')

@section('title', '登录')

@section('stylesheets')

    <style>
        .text-above { margin-top: 40px; margin-bottom: 20px; }
        .text-below { margin-top: 10px; }
    </style>

@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 text-center">
                <p class="text-above lead">微信扫一扫 3秒极速登录</p>
                <img src="{{ $qrcode }}" alt="使用微信扫码登录" class="img-thumbnail img-responsive">
                <h4 class="text-below"><span id="msg" class="label label-success">使用微信扫码登录</span></h4>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>

        var interval = 3000;
        var times = {{ $expire*1000 }} / interval;

        $(function(){
            ajax_check();
        });
        function ajax_check() {
            $.ajax({
                url             : "{{ route('login.wechat.check') }}",
                headers         : { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                type            : "POST",
                cache           : false,
                contentType     : false,
                processData     : false,
                success         : function( r ){
                    if (r.authorized) {
                        window.location.href = r.intended_url;
                    }
                    else if (-- times > 0) {
                        setTimeout(ajax_check, interval);
                    }
                    else {
                        $('#msg').html('二维码过期，请刷新');
                    }
                }
            }, 'json');
        }
    </script>

@endsection