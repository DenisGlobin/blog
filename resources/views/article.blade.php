@extends('layouts.app')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">Article</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column align-items-start">
                <h2 class="blog-post-title">{{ $article->title }}</h2>
                <p class="blog-post-meta">
                    {!! $article->created_at !!} by
                    <a href="#">{{ $article->user->name }}</a>
                </p>

                <p class="card-text mb-auto">{!! $article->full_text !!}</p>
                <hr/>
            </div>
        </div>
    </div>
@endsection