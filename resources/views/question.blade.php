@extends('layouts.app')

@section('active_question', 'active')

@section('stylesheets')

    <style>
        .panel-title a {
            display: block;
            text-decoration: none;
            font-size: 14px;
        }
    </style>

@endsection

@section('title', '常见问题')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                    @foreach ($questions as $question)
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="heading-{{ $loop->iteration }}">
                            <div class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ $loop->iteration }}" aria-expanded="false" aria-controls="collapse-{{ $loop->iteration }}">
                                    {{ $question['title'] }}
                                </a>
                            </div>
                        </div>
                        <div id="collapse-{{ $loop->iteration }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-{{ $loop->iteration }}">
                            <div class="panel-body">
                                {!! $question['body'] !!}
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>
        var i = window.location.hash.substr(1);
        if (i) {
            $('#heading-'+i+' a').click();
        }
    </script>

@endsection
