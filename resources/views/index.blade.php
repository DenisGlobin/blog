@extends('layouts.app')

@section('content')

    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">{{ $title }}</h3>

            @if (isset(Auth::user()->banned_until))
                @include('inc.banned')
            @endif

            @if(! $articles->isEmpty())
                @foreach($articles as $article)
                    <div class="blog-post">
                        <h2 class="blog-post-title">{{ $article->title }}</h2>
                        <p class="blog-post-meta">
                            {!! $article->created_at !!} by
                            @auth
                                <a href="{{ route('user.info', ['id' => $article->user->id]) }}">{{ $article->user->name }}</a>
                            @endauth
                            @guest
                                <a href="{{ route('user.articles', ['id' => $article->user->id]) }}">{{ $article->user->name }}</a>
                            @endguest
                        </p>

                        <p>{!! $article->short_text !!}</p>
                        <div class="container">
                            <p class="blog-post-meta">Tags:
                            @foreach($article->tags as $tag)
                                <a class="text-secondary" href="{{ route('tag', ['tag' => $tag->name]) }}">[{{ $tag->name }}]   </a>
                            @endforeach
                            </p>
                        </div>
                        <div class="container">
                            <div class="row justify-content-between">
                                <div class="col-5">
                                    <a href="{{ route('article', ['id' => $article->id]) }}">@lang('article.read_more')</a>
                                </div>
                                {{-- Show Delete and Edit buttons for the article's owner --}}
                                @can(['update', 'delete'], $article)
                                    <div class="col-3">
                                        <a class="btn btn-outline-success btn-sm" href="{{ route('edit.article.form', ['id' => $article->id]) }}" role="button">Edit</a>
                                        <button type="button" class="btn btn-outline-danger btn-sm" value="{{ $article->id }}" name="del">Delete</button>
                                    </div>
                                {{-- Show Delete button for Admin --}}
                                @elsecan('delete', $article)
                                    <div class="col-3">
                                        <button type="button" class="btn btn-outline-danger btn-sm" value="{{ $article->id }}" name="del">Delete</button>
                                    </div>
                                @endcan
                            </div>
                        </div>
                        <hr/>
                    </div>
                @endforeach

                <div class="row justify-content-center">
                    {!! $articles->render() !!}
                </div>
            @else
                <span>@lang('article.no_articles')</span>
            @endif
    </div>
@endsection

@section('js')
    <script>
        $(function(){
            $(".btn-outline-danger").on('click', function () {
                let id = $(this).attr("value");
                alertify.confirm("@lang('article.delete')", function () {
                    $.ajax({
                        type: "delete",
                        url: "{{ route('article.delete') }}",
                        data: {_token: "{{ csrf_token() }}", id:id},
                        complete: function () {
                            alertify.success("@lang('article.delete_ok')");
                            location.reload();
                        }
                    })
                }, function () {
                    alertify.error("@lang('article.cancel')");
                });
            })
        });
    </script>
@endsection

@section('archive')
    <div class="p-3">
        <h4 class="font-italic">@lang('menu.archives')</h4>
        <ol class="list-unstyled mb-0">
            @foreach($dates as $date)
                <li class="blog-post-meta">
                    <a href="{{ route('articles.from', ['month' => $date['month'], 'year' => $date['year']]) }}">{!! $date['date'] !!}</a>
                    {!! ' [' . $date['count'] . ']' !!}
                </li>
            @endforeach
        </ol>
    </div>
@endsection

@section('tags')
    <div class="p-3 mb-3 bg-light rounded">
        <h4 class="font-italic">@lang('menu.tags')</h4>
        <p class="mb-0">
            @foreach($tags as $tag)
                <a class="text-dark" href="{{ route('tag', ['tag' => $tag->name]) }}">{{ $tag->name }}</a>,
            @endforeach
        </p>
    </div>
@endsection