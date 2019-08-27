@extends('layouts.app')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">Add new article</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column">
                <form action="{{ route('edit.article') }}" method="post">
                    @csrf
                    <input id="id" name="id" type="hidden" value="{{ isset($article->id) ? $article->id : null }}">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                               value="{{ isset($article) ? $article->title : 'Type title of the article' }}">
                        @error('title')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="fullText">Text</label>
                        <textarea class="form-control @error('fullText') is-invalid @enderror" id="fullText" name="fullText" rows="5">{{ isset($article) ? $article->full_text : 'Type text' }}</textarea>
                        @error('fullText')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            @if($article->is_active == true)
                                <input class="form-check-input" type="checkbox" id="isActive" name="isActive" checked>
                                <label class="form-check-label" for="gridCheck">Publish new article</label>
                            @else
                                <input class="form-check-input" type="checkbox" id="isActive" name="isActive">
                                <label class="form-check-label" for="gridCheck">Publish new article</label>
                            @endif
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
                <br>
                <a href="{{ route('article', ["id" => $article->id]) }}">Return back</a>
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection