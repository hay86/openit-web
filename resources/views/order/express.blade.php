@extends('layouts.app')

@section('title', '查看物流')

@section('stylesheets')

    <style>
        .panel-title a {
            display: block;
            text-decoration: none;
            font-size: 14px;
        }
        .panel-body .icon {
            float: left;
            width: 70px;
        }
        .panel-body .icon img {
            width: 60px;
        }
        ul.list {
            border-left: solid 2px #ddd;
            padding-left: 20px;
            margin: 0;
        }
        ul.list li {
            position: relative;
            border-bottom: solid 1px #ddd;
            list-style: none;
            padding: 10px 0;
            color: #999;
        }
        .circle {
            border-radius: 50%;
            -moz-border-radius: 50%;
            -webikit-border-radius: 50%;
            -o-border-radius: 50%;
        }
        /*注意调整left值，为-(ul的padding-left+ul的border-left/2+原点宽度/2)px*/
        ul.list li div.circle {
            top: 15px;
            left: -26px;
            position: absolute;
            width: 10px;
            height: 10px;
            background: #ddd;
        }
        ul.list li.first {
            color: green;
            padding-top: 0
        }
        ul.list li.first div.circle {
            top: -2px;
            background-color: rgba(0,128,0,0.25);
            width: 20px;
            height: 20px;
            left: -31px;
        }
        ul.list li.first div.circle b {
            display: block;
            width: 80%;
            height: 80%;
            background: #080;
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -40%;
            margin-top: -40%;
        }
        ul.list li.list div.circle b {
            display: block;
        }
        ul.list li.last {
            border-bottom: none;
            padding-bottom: 0;
        }
    </style>

@endsection

@section('content')

    <div class="container">
        @if (count($expresses) == 0)
            <div class="row">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <h1><small>没有物流信息 ╮(╯_╰)╭</small></h1>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                        @foreach ($expresses as $express)
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading-{{ $loop->iteration }}">
                                <div class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ $loop->iteration }}" aria-expanded="{{ $loop->iteration == 1 ? 'true' : 'false' }}" aria-controls="collapse-{{ $loop->iteration }}">
                                        包裹 #{{ count($expresses) - $loop->iteration + 1 }}
                                    </a>
                                </div>
                            </div>
                            <div id="collapse-{{ $loop->iteration }}" class="panel-collapse collapse {{ $loop->iteration == 1 ? 'in' : '' }}" role="tabpanel" aria-labelledby="heading-{{ $loop->iteration }}">
                                <div class="panel-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <div class="icon"><img src="{{ courier_firm($express->courier_firm)['picture'] }}"></div>
                                            <div>承运：{{ courier_firm($express->courier_firm)['name'] }}</div>
                                            <div>编号：{{ $express->courier_num }}</div>
                                            <div>电话：{{ courier_firm($express->courier_firm)['phone'] }}</div>
                                        </li>
                                    </ul>

                                    <ul class="list">
                                        @if ($express->track_info)
                                        @foreach ($express->track_info->track as $track)
                                            <li class="{{ $loop->iteration == 1 ? 'first' : '' }}">
                                                <p>{{ $track->desc }}</p>
                                                <div>{{ $track->date }}</div>
                                                <div class="circle"><b class="circle"></b></div>
                                            </li>
                                        @endforeach
                                        @else
                                            <li class="first">
                                                <p>提示：没有跟踪数据</p>
                                                <div>{{ date('Y-m-d H:i:s') }}</div>
                                                <div class="circle"><b class="circle"></b></div>
                                            </li>
                                        @endif
                                        <li class="last">
                                            <p>发往：{{ $express->address->address_string }} {{ $express->address->contact_string }}</p>
                                            <div>{{ $express->created_at }}</div>
                                            <div class="circle"><b class="circle"></b></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection