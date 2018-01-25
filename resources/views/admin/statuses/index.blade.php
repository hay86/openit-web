@extends('layouts.app')

@section('title', '所有订单')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>订单统计（最近{{ count($rows) }}天）</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>日期</th>
                        @foreach ($statuses as $status)
                            <th>{{ order_status($status) }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr>
                                <th>{{ $row->id }}</th>
                                @if ($loop->iteration == 1)
                                    @foreach ($statuses as $status)
                                        <td><a href="{{ route('admin.orders.index') . '?status=' . $status }}">{{ $row['s'.$status] }}</a></td>
                                    @endforeach
                                @else
                                    @foreach ($statuses as $status)
                                        <td>{{ $row['s'.$status] }}</td>
                                    @endforeach
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection