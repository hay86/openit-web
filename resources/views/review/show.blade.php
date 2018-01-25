@extends('layouts.app')

@section('title', '红包中心')

@section('stylesheets')

    <style>
        h1 { margin-bottom:40px; }
        a:hover, a:active, a:visited, a:link { text-decoration:none; }
        .review { padding:5px 10px; position:relative; bottom:60px; color:white; }
        .select { background-color:#ffaa00; border-radius:5px; }
    </style>

@endsection

@section('content')

    <div class="container">
        @if (empty($title))
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1><small>没有红包 ╮(╯_╰)╭</small></h1>
                </div>
            </div>
        @else
            @if (empty($review))
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1><small>{{ $title }}</small></h1>
                </div>
            </div>
            <div class="row">
                <form role="form" method="POST" action="{{ route('review.update', $id) }}">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-group col-xs-4 col-md-2 col-md-offset-3 text-center">
                        <a href="javascript:void(0);" class="hongbao" id="1">
                            <img class="img-responsive" src="http://static.openit.shop/hongbao.png">
                            <span class="review select">超好吃</span>
                        </a>
                    </div>
                    <div class="form-group col-xs-4 col-md-2 text-center">
                        <a href="javascript:void(0);" class="hongbao"  id="0">
                            <img class="img-responsive" src="http://static.openit.shop/hongbao.png">
                            <span class="review">没感觉</span>
                        </a>
                    </div>
                    <div class="form-group col-xs-4 col-md-2 text-center">
                        <a href="javascript:void(0);" class="hongbao"  id="-1">
                            <img class="img-responsive" src="http://static.openit.shop/hongbao.png">
                            <span class="review">不好吃</span>
                        </a>
                    </div>
                    <div class="form-group col-xs-12 col-md-6 col-md-offset-3">
                        <textarea id="review" name="review" class="form-control" placeholder="补充理由"></textarea>
                    </div>
                    <div class="form-group col-xs-12 col-md-6 col-md-offset-3">
                        <button type="submit" class="btn btn-warning btn-block"><h4>打开红包</h4></button>
                    </div>
                    <input type="hidden" id="score" name="score" value="1">
                </form>
            </div>
            @elseif ($review->prize == 0)
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1><small>很抱歉！</small></h1>
                    <p class="lead">您没抢到红包，再来一次吧！</p>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1><small>恭喜您！</small></h1>
                    <p class="lead">¥{{ $review->prize }} 红包入手，打开 <a href="{{ route('account.coupons') }}">我的优惠券</a></p>
                </div>
            </div>
            @endif
        @endif
    </div>

@endsection

@section('scripts')

    <script>
        $(function(){
            $('.hongbao').click(function(){
                $('.review').removeClass('select');
                $(this).find('.review').addClass('select');
                $('#score').val($(this).attr('id'));
            });
        });
    </script>

@endsection