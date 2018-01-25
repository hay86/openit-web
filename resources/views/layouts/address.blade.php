@extends('layouts.app')

@section('title', '新增地址')

@section('stylesheets')

    <style>
        select.form-control { appearance: none; -webkit-appearance: none; -moz-appearance: none; }
        div.select-left { padding-right: 4px; }
        div.select-middle { padding-left: 8px; padding-right: 8px; }
        div.select-right { padding-left: 4px; }
        .control-label { padding-right: 0; }
    </style>

    @yield('stylesheets-with-modal')

@endsection

@section('content-with-modal')

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <ul id="address-box" class="list-group {{ empty($user->address) ? 'hide' : '' }}">
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
                <input type="hidden" id="address_id" name="address_id" value="{{ empty($user->address) ? 0 : $user->address->id }}">
            </div>
        </div>
    </div>

@endsection

@section('content')

    @yield('content-with-modal')

    <!-- address modal, $user is required -->
    <div class="modal fade" id="address-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-center" id="modal-title">收货地址</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="username" class="col-sm-3 col-xs-3 control-label">收货人</label>
                            <div class="col-sm-6 col-xs-9">
                                <input type="text" class="form-control" name="username" id="username" placeholder="姓名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mobile" class="col-sm-3 col-xs-3 control-label">手机</label>
                            <div class="col-sm-6 col-xs-9">
                                <input type="number" class="form-control" name="mobile" id="mobile" placeholder="收货人手机">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="region" class="col-sm-3 col-xs-3 control-label">所在地区</label>
                            <div class="col-sm-2 col-xs-3 select-left">
                                <select name="province" id="province" class="form-control"></select>
                            </div>
                            <div class="col-sm-2 col-xs-3 select-middle">
                                <select name="city" id="city" class="form-control"></select>
                            </div>
                            <div class="col-sm-2 col-xs-3 select-right">
                                <select name="district" id="district" class="form-control"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="detail" class="col-sm-3 col-xs-3 control-label">详细地址</label>
                            <div class="col-sm-6 col-xs-9">
                                <input type="text" class="form-control" name="detail" id="detail" placeholder="街道门牌信息">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-xs-offset-3 col-sm-6 col-xs-9">
                                <button id="address-save" type="button" class="btn btn-primary">保存</button>
                                <span id="error-msg"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="text-left">
                        <h5>历史</h5>
                        <div id="address-history" class="list-group">
                            @foreach ($user->addresses as $address)
                                <a id="{{ $address->id }}" href="javascript:void(0);" class="list-group-item {{ !empty($user->address) && $address->id == $user->address->id ? 'active' : '' }}" onclick="address_click(this)">
                                    <div id="contact" class="contact">{{ $address->contact_string}}</div>
                                    <div id="address" class="address">{{ $address->address_string }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script src="http://static.openit.shop/postcode-20170509.js"></script>
    <script>
        var STR_TPL_1 = '<option value="" disabled selected>省份</option>';
        var STR_TPL_2 = '<option value="" disabled selected>城市</option>';
        var STR_TPL_3 = '<option value="" disabled selected>区县</option>';
        var STR_TPL_4 = '<option value="{0}">{0}</option>';
        var STR_TPL_5 = '<code>{0}</code>';
        var STR_TPL_6 = '<a id="{0}" href="javascript:void(0);" class="list-group-item" onclick="address_click(this)">' +
            '<div id="contact" class="contact">{1} {2}</div>' +
            '<div id="address" class="address">{3}{4}{5}{6}</div>' +
            '</a>';

        $(function(){
            // reset form
            form_reset();

            // set responsive when options changed
            form_options_change();

            // submit form data via ajax
            form_submit();

            // select default province and city
            select_default_city();
        });
        function form_reset() {
            $('#username'   ).val('');
            $('#mobile'     ).val('');
            $('#detail'     ).val('');
            $('#province'   ).html(STR_TPL_1);
            $('#city'       ).html(STR_TPL_2);
            $('#district'   ).html(STR_TPL_3);
            $('#error-msg'  ).html('');

            $.each(kf_province(), function(i, v){
                $('#province').append(STR_TPL_4.format(v));
            });
        }
        function form_val() {
            var r = {};
            r.username  = $('#username').val();
            r.mobile    = $('#mobile').val();
            r.detail    = $('#detail').val();
            r.province  = $('#province option:selected').val();
            r.city      = $('#city option:selected').val();
            r.district  = $('#district option:selected').val();
            return r;
        }
        function form_data() {
            var r = form_val();
            var data = new FormData();
            data.append('username', r.username);
            data.append('mobile',   r.mobile);
            data.append('detail',   r.detail);
            data.append('province', r.province);
            data.append('city',     r.city);
            data.append('district', r.district);
            if (r.province != '' && r.city != '' && r.district != '')
                data.append('postcode', kf_postcode(r.province, r.city, r.district));
            return data;
        }
        function form_options_change() {
            $('#province').on('change', function(){
                $('#city').html(STR_TPL_2);
                var r = form_val();
                $.each(kf_city(r.province), function(i, v){
                    $('#city').append(STR_TPL_4.format(v));
                });
            });
            $('#city').on('change', function(){
                $('#district').html(STR_TPL_3);
                var r = form_val();
                $.each(kf_district(r.province, r.city), function(i, v){
                    $('#district').append(STR_TPL_4.format(v));
                });
            });
        }
        function form_submit() {
            $('#address-save').click(function(){
                $('#address-save').prop('disabled', true);
                $.ajax({
                    url             : "{{ route('address.store') }}",
                    headers         : { 'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content') },
                    type            : "POST",
                    data            : form_data(),
                    cache           : false,
                    contentType     : false,
                    processData     : false,
                    success         : function( r ){
                        if (r.status == 'failed') {
                            $('#error-msg').html(STR_TPL_5.format(r.reason));
                        }
                        else {
                            $('#address-history').prepend(STR_TPL_6.format(
                                r.address.id,
                                r.address.username,
                                r.address.mobile,
                                r.address.province,
                                r.address.city,
                                r.address.district,
                                r.address.detail
                            ));
                            $('#error-msg').html('');
                            $('#address-history a#'+r.address.id).click();
                        }
                        $('#address-save').prop('disabled', false);
                    },
                    error           : function(){
                        $('#error-msg').html(STR_TPL_5.format('保存失败，请重试！'));
                        $('#address-save').prop('disabled', false);
                    }
                }, 'json');
            });
        }
        function address_click(e) {
            form_reset();
            select_default_city();
            $('#address-box').removeClass('hide');
            $('#address-history a').removeClass('active');
            var elem = $(e);
            elem.addClass('active');
            $('#active-contact').html(elem.find('#contact').html());
            $('#active-address').html(elem.find('#address').html());
            $('#address_id').val(elem.attr('id'));
            $('.close').click();
        }
        function select_default_city() {
            $.ajax({
                url             : "{{ route('proxy.ip') }}",
                type            : "GET",
                data            : "",
                cache           : false,
                contentType     : false,
                processData     : false,
                success         : function( r ){
                    if (r.province) {
                        var prov = kf_provabbr(r.province);
                        if ($("#province option[value=" + prov + "]").length > 0)
                            $("#province").val(prov).change();
                    }
                    if (r.city) {
                        var city = r.city;
                        if ($("#city option[value=" + city + "]").length > 0)
                            $("#city").val(city).change();
                    }
                }
            }, 'json');
        }
    </script>

    @yield('scripts-with-modal')

@endsection