@extends('layouts.app')

@section('title', '管理页面')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>管理员 {{ Auth::user()->name }}，你好！</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                @foreach ($buttons as $button)
                <div class="col-sm-4 text-center">
                    <h1>
                        <a href="{{ $button['url'] }}" class="btn btn-primary btn-block btn-lg">
                            <h4>{{ $button['name'] }}</h4>
                        </a>
                    </h1>
                </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection