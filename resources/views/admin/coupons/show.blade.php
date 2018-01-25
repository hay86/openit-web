@extends('layouts.app')

@section('title', $user->name . '的优惠券')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1><small>{{ $user->name }}的优惠券</small></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-primary">
                    <div class="panel-heading">优惠券列表</div>
                    <table class="table table-condensed table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>折扣</th>
                            <th>用户</th>
                            <th>过期</th>
                            <th>创建于</th>
                            <th>修改于</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($coupons as $coupon)
                            <tr>
                                <th>{{ $coupon->id }}</th>
                                <td>¥{{ $coupon->discount }}</td>
                                <td><a href="{{ route('admin.coupons.show', $coupon->user->id) }}" class="btn btn-link btn-xs">{{ $coupon->user->name }}</a></td>
                                <td>{{ $coupon->expired_at }}</td>
                                <td>{{ $coupon->created_at->diffForHumans() }}</td>
                                <td>{{ $coupon->updated_at->diffForHumans() }}</td>
                                <td class="text-right">
                                    @if ($coupon->deleted_at)
                                        已删除
                                    @else
                                    <form role="form" method="POST" class="delete" action="{{ route('admin.coupons.destroy', $coupon->id) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-primary btn-sm">删除</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">操作</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <a href="{{ route('admin.coupons.create') }}?user_id={{ $user->id }}" class="btn btn-primary btn-block">创建优惠券</a>
                        </div>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-success btn-block">返回上一级</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    {!! $coupons->links() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(".delete").on("submit", function(){
            return confirm("确定要删除这个优惠券么？");
        });
    </script>
@endsection