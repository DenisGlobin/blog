@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Articles</div>

                    <div class="card-body">

                        <div class="messages">

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <span>
                                            <a href="#">{{ $article->title }}</a>
                                        </span>
                                        <span class="pull-right label label-info">{!! $article->created_at !!}</span>
                                    </h3>
                                </div>

                                <div class="panel-body">
                                    {!! $article->full_text !!}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection