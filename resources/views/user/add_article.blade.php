@extends('layouts.app')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">@lang('article.new_article')</h3>

        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column">
                <form id="formArticle" method="post" action="{{ route('article.add') }}">
                    @csrf
                    <div class="form-group">
                        <label for="title">@lang('article.article_title')</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title">
                        @error('title')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="fullText">@lang('article.article_txt')</label>
                        <textarea class="form-control @error('fullText') is-invalid @enderror" id="fullText" name="fullText" rows="5"></textarea>
                        @error('fullText')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tags" id="tagsLabel">@lang('article.article_tags')</label>
                        <div class="input-group">
                            <input type="text" id="tags" name="tags" list="tagsList">
                            <datalist id="tagsList">
                                @foreach($tags as $tag)
                                    <option>{{ $tag->name }}</option>
                                @endforeach
                            </datalist>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="addTag">Add</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isActive" name="isActive" checked>
                            <label class="form-check-label" for="gridCheck">@lang('article.article_publish')</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('article.save_btn')</button>
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

@section('js')
    <script>
        // Add Tag
        $(function () {
            $("button#addTag").on('click', function () {
                let tag = $("input#tags").val();
                if (!!tag) {
                    $("label#tagsLabel").after("" +
                        "<button type='button' class='btn btn-light btn-sm fade show' onclick='deleteTag(event)' value='" + tag + "'>" +
                        tag + "<span class='badge badge-secondary' aria-hidden='true'>&times;</span>\n" +
                        "</button>\n");
                    $("input#tags").val('');
                }
            })
        });
        // Delete Tag
        function deleteTag(event) {
            let domElement =$(event.currentTarget);
            domElement.remove();
        }
        // Add tags to post's data
        formArticle.onsubmit = (e) => {
            // Delete add tags field
            let data = new FormData(e.target);
            data.delete('tags');
            // Add tags to form
            $.each($("button.fade"), function () {
                $("form#formArticle").append('<input type="hidden" name="tags[]" value="'+$(this).val()+'">');
            });
        }
    </script>
@endsection