@extends('layouts.app')

@section('title', '授权页')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 text-center">
            <h1>
                <small>
                    @if ($success)
                    <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
                    <span>登录成功</span>
                    @else
                    <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
                    <span>二维码过期，请刷新</span>
                    @endif
                </small>
            </h1>
        </div>
    </div>
</div>

@endsection

@section('scripts')

    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script>
        wx.config({!! $js->config(['closeWindow']) !!});
        wx.ready(function(){ setTimeout(function(){ wx.closeWindow(); }, 1000); });
    </script>

@endsection