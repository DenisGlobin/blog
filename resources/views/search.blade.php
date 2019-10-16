@extends('layouts.app')

@section('content')

    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">Search</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column">
                <form method="post" action="{{ route('searching') }}">
                    @csrf
                    <div class="form-group">
                        <label for="title">Type your reqest</label>
                        <input type="text" class="form-control @error('query') is-invalid @enderror" id="query" name="query">
                        @error('query')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

        <div class="p-3 mb-3 bg-light rounded">
            <h4 class="font-italic">@lang('menu.tags')</h4>
            <p class="mb-0">
                @foreach($tags as $tag)
                    <a class="text-dark" href="{{ route('tag', ['tag' => $tag->name]) }}">{{ $tag->name }}</a>,
                @endforeach
            </p>
        </div>
    </div>

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