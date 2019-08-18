@extends('layouts.app')

@section('content')

        <div class="col-md-8 blog-main">
            <h3 class="pb-3 mb-4 font-italic border-bottom">Articles</h3>

                @if(! $articles->isEmpty())
                    @foreach($articles as $article)
                        <div class="blog-post">
                            <h2 class="blog-post-title">{{ $article->title }}</h2>
                            <p class="blog-post-meta">
                                {!! $article->created_at !!} by
                                <a href="{{ route('user.info', ['id' => $article->user->id]) }}">{{ $article->user->name }}</a>
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
                    <span>There are no articles yet.</span>
                @endif
        </div>

@endsection
