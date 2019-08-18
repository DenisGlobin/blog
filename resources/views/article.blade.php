@extends('layouts.app')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">Article</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column align-items-start">
                <h2 class="blog-post-title">{{ $article->title }}</h2>
                <p class="blog-post-meta">
                    {!! $article->created_at !!} by
                    <a href="{{ route('user.info', ['id' => $article->user->id]) }}">{{ $article->user->name }}</a>
                </p>

                <p class="card-text mb-auto">{!! $article->full_text !!}</p>
                <hr/>
            </div>
        </div>
        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column">
                <h2 class="blog-post-title">Comments</h2>
                @include('inc.comments.add_comment_form')
                <br>
                @include('inc.comments.comments')
            </div>
        </div>
    </div>
@endsection