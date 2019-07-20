@extends('layouts.app')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">Add new article</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column">
                <form action="{{ route('add.article') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="inputAddress2">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Type title of the article">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Type text of the Article</label>
                        <textarea class="form-control" id="fullText" name="fullText" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isActive" name="isActive" checked>
                            <label class="form-check-label" for="gridCheck">Publish new article</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add article</button>
                </form>
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection