@extends('layouts.app')

@section('title', '邀请好友')

@section('stylesheets')

    <style>
        .text-above { margin-top: 40px; margin-bottom: 20px; }
        .text-below { margin-top: 10px; }
        select.form-control { appearance: none; -webkit-appearance: none; -moz-appearance: none; }
    </style>

@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 text-center">
                <p class="text-above lead">长按二维码 发送给朋友</p>
                @foreach ($qrcodes as $qrcode)
                    <img id="qr{{ $loop->iteration }}" src="{{ $qrcode }}" alt="推荐好友扫码下单" class="img-thumbnail img-responsive {{ $loop->iteration == 1 ? '' : 'hide' }}">
                @endforeach
                <h4 class="text-below"><span id="msg" class="label label-success">使用微信扫码下单</span></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                @if (count($coupons) > 1)
                    <div class="form-group">
                        <label for="coupons" class="control-label">赠送优惠券</label>
                        <select class="form-control" id="coupons">
                            @foreach ($coupons as $c)
                                <option value="qr{{ $loop->iteration }}">{{ empty($c) ? '不使用优惠券' : '¥' . $c->discount . ' 优惠券（有效期至 ' . date('Y-m-d', strtotime($c->expired_at)) . '）' }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('scripts')

<script>
    $(function(){
        if ($('#coupons').length) {
            $('#coupons').on('change', function () {
                var qri = $('#coupons option:selected').val();
                $('img').addClass('hide');
                $('#' + qri).removeClass('hide');
            });
        }
    });
</script>

@endsection
