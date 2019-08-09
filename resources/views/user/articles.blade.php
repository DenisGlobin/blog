@extends('layouts.app')

@section('content')

    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">My articles</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column align-items-start">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if(! $articles->isEmpty())
                    @foreach($articles as $article)
                        <div class="blog-post">
                            <h2 class="blog-post-title">{{ $article->title }}</h2>
                            <p class="blog-post-meta">
                                {!! $article->created_at !!} by
                                <a href="#">{{ $article->user->name }}</a>
                            </p>

                            <p>{!! $article->short_text !!}</p>
                            <a href="{{ route('article', ['id' => $article->id]) }}">Read more</a>
                            <hr/>
                        </div>
                    @endforeach

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="text-center">
                        {!! $articles->render() !!}
                    </div>
                @else
                    <span>You have not added any articles yet.</span>
                @endif

            </div>
        </div>
    </div>

@endsection
