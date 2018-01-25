@extends('layouts.address')

@section('active_order_create', 'active')

@section('title', '购买')

@section('stylesheets-with-modal')

    <style>
        .order .title {
            font-size: 20px;
            padding-top: 8px;
        }
        .order .title a {
            text-decoration: none;
            display: block;
            color: #333;
        }
        .order .title .glyphicon {
            font-size: 14px;
            float: right;
            margin-top: 6px;
        }
        .order .subtitle {
            font-size: 14px;
            padding-top: 5px;
            color: #bbb;
        }
        .order .price {
            font-size: 14px;
            padding-top: 8px;
            color: red;
        }
        .order .price .big {
            font-size: 20px;
        }
        .order .option {
            display: inline;
            float: left;
            margin-right: 8px;
            margin-bottom: 8px;
        }
        .order .clear-option {
            clear: both;
        }
        .order .hint {
            font-size: 12px;
            color: red;
        }
        .order .info {
            margin-bottom: 15px;
        }
        .carousel {
            margin-left: -15px;
            margin-right: -15px;
            margin-top: -11px;
            margin-bottom: 10px;
        }
        ol.carousel-indicators {
            width: 60%;
            left: 50%;
            bottom: 5px;
        }
        ol.carousel-indicators li, ol.carousel-indicators li.active {
            width: 7px;
            height: 7px;
            border-radius: 7px;
        }
    </style>

@endsection

@section('content-with-modal')

    <div class="container order">
        @if (count($products) == 0)
            <div class="row">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <h1><small>没有该商品 ╮(╯_╰)╭</small></h1>
                </div>
            </div>
        @else
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div id="carousel-generic" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        @foreach ($products[0]->article->images as $img)
                        <li data-target="#carousel-generic" data-slide-to="{{ $loop->iteration-1 }}" class="{{ $loop->iteration==1 ? 'active' : '' }}"></li>
                        @endforeach
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        @foreach ($products[0]->article->images as $img)
                        <div class="item {{ $loop->iteration==1 ? 'active' : '' }}">
                            <img src="{{ \App\Image::gen_md_wide_url($img->id) }}" class="img-responsive">
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="title">
                    <a href="{{ route('blog.show', $products[0]->article_id) }}">
                        <span id="title_above">{{ $products[0]->article->title }}</span>
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    </a>
                </div>
                <div class="subtitle">{{ $products[0]->article->subtitle }}</div>
                <div class="price">¥<span id="price_above" class="big">{{ $products[0]->price }}@if($products[0]->price < $products[count($products)-1]->price)-{{ $products[count($products)-1]->price }}@endif</span></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form role="form" method="POST" action="{{ route('order.store') }}">
                    {{ csrf_field() }}
                    <hr>
                    <div class="form-group {{ empty($tastes) ? 'hide' : '' }}">
                        <label for="notes" class="control-label">口味偏好</label>
                        <div id="taste_list">
                            @foreach ($tastes as $taste => $note)
                                <div class="list-group option">
                                    <a href="javascript:void(0);" class="list-group-item" onclick="taste_click(this)" title="{{ $note }}">{{ $taste }}</a>
                                </div>
                            @endforeach
                            <div class="clear-option"></div>
                        </div>
                        <input type="hidden" id="notes" name="notes" value="">
                    </div>
                    <div class="form-group {{ count($products) <= 1 ? 'hide' : '' }}">
                        <label for="product_id" class="control-label">订购期限</label>
                        <div id="product_list">
                            @foreach ($products as $product)
                            <div class="list-group option">
                                <a id="{{ $product->id }}" href="javascript:void(0);" class="list-group-item" onclick="product_click(this,{{ $product->price }})" title="{{ $product->displayName }}">{{ duration($product->times) }}({{ $product->times }}盒)</a>
                            </div>
                            @endforeach
                            <div class="clear-option"></div>
                        </div>
                        <input type="hidden" id="product_id" name="product_id" value="">
                    </div>
                    <div class="form-group">
                        <label for="prefer_day" class="control-label">送达时间</label>
                        <div id="prefer_days">
                            @foreach ($prefer_days as $id)
                            <div class="list-group option">
                                <a id="{{ $id }}" href="javascript:void(0);" class="list-group-item" onclick="day_click(this)" title="首次配送：{{ date('n月j日', express_date($id)) }}">{{ week_day($id) }}</a>
                            </div>
                            @endforeach
                            <div class="clear-option"></div>
                        </div>
                        <div id="express_hint" class="hint"></div>
                        <input type="hidden" id="prefer_day" name="prefer_day" value="">
                    </div>
                    <div class="form-group">
                        <label for="address_id" class="control-label">收货地址</label>
                        <ul id="address-box" class="list-group info {{ empty($user->address) ? 'hide' : '' }}">
                            <li class="list-group-item">
                                <div id="active-contact" class="active-contact">
                                    {{ empty($user->address) ? '' : $user->address->contact_string }}
                                </div>
                                <div id="active-address" class="active-address">
                                    {{ empty($user->address) ? '' : $user->address->address_string }}
                                </div>
                            </li>
                        </ul>
                        <button type="button" class="btn btn-default btn-block" data-toggle="modal" data-target="#address-modal">{{ empty($user->address) ? '新增地址' : '切换地址' }}</button>
                        <input type="hidden" id="address_id" name="address_id" value="{{ empty($user->address) ? '' : $user->address->id }}">
                    </div>
                    @if (count($coupons) > 1)
                    <div class="form-group">
                        <label for="coupons" class="control-label">选择优惠券</label>
                        <select class="form-control" id="coupons">
                            @foreach ($coupons as $c)
                                @if (empty($c))
                                    <option id="" value="0">不使用优惠券</option>
                                @else
                                    <option id="{{ $c->id }}" value="{{ $c->discount }}">{{ '¥' . $c->discount . ' 优惠券（有效期至 ' . date('Y-m-d', strtotime($c->expired_at)) . '）' }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <hr>
                    <div class="form-group text-right">
                        <div>商品金额：<span class="price">¥<span id="price_below">0</span></span></div>
                        <div>+ 运费：<span class="price">¥<span id="express_fee">0</span></span></div>
                        @if (!empty($coupon))
                        <div>- 优惠：<span class="price">¥<span id="coupon_fee">{{ $coupon->discount }}</span></span></div>
                        @endif
                        <div>付款金额：<span class="price">¥<span id="cash_fee" class="big">0</span></span></div>
                        @if (!empty($referer_id))
                        <input type="hidden" id="referer_id" name="referer_id" value="{{ $referer_id }}">
                        @endif
                        @if (!empty($coupon))
                        <input type="hidden" id="coupon_id" name="coupon_id" value="{{ $coupon->id }}">
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-warning btn-block"><h4>确认下单</h4></button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

@endsection

@section('scripts-with-modal')

    <script>
        $(function(){
            if ($('#coupons').length) {
                $('#coupons').on('change', function () {
                    if ($('#coupon_fee').length)
                        $('#coupon_fee').html($('#coupons option:selected').val());
                    if ($('#coupon_id').length)
                        $('#coupon_id').val($('#coupons option:selected').attr('id'));
                    update_total();
                });
            }
            if ($('#taste_list a').length) {
                $('#taste_list a')[0].click();
            }
            if ($('#product_list a').length) {
                $('#product_list a')[0].click();
            }
            $('.carousel').swipe( {
                swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
                    if (direction == 'left') $(this).carousel('next');
                    if (direction == 'right') $(this).carousel('prev');
                }
            });
        });
        function taste_click(e) {
            $('#taste_list a').removeClass('active');
            var elem = $(e);
            elem.addClass('active');
            $('#notes').val(elem.attr('title'));
        }
        function product_click(e, price) {
            $('#product_list a').removeClass('active');
            var elem = $(e);
            elem.addClass('active');
            $('#title_above').html(elem.attr('title'));
            $('#price_above').html(price);
            $('#price_below').html(price);
            $('#product_id').val(elem.attr('id'));
            update_total();
        }
        function day_click(e) {
            $('#prefer_days a').removeClass('active');
            var elem = $(e);
            elem.addClass('active');
            $('#express_hint').html(elem.attr('title'));
            $('#prefer_day').val(elem.attr('id'));
        }
        function update_total() {
            var cash = parseFloat($('#price_below').html()) + parseFloat($('#express_fee').html())
            if ($('#coupon_fee').length)
                cash -= parseFloat($('#coupon_fee').html());
            cash = Math.round(cash*100)/100;
            $('#cash_fee').html(Math.max(cash, 0));
        }
    </script>

@endsection