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

                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-5">
                            <a href="{{ route('articles') }}">{{ __('article.main_page') }}</a>
                        </div>
                        @if($article->user->id == Auth::id())
                            <div class="col-3">
                                <a class="btn btn-outline-primary btn-sm" href="{{ route('show.editarticle.form', ['id' => $article->id]) }}" role="button">Edit</a>
                                <button type="button" class="btn btn-outline-danger btn-sm" value="{{ $article->id }}" name="del">Delete</button>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column">
                <h2 class="blog-post-title">Comments</h2>
                @auth
                    @include('inc.comments.add_comment_form')
                @endauth
                <br>
                @include('inc.comments.comments')
            </div>
        </div>
    </div>
@endsection

@section('js_notify')
    <script>
        // For article
        $(function(){
            $(".btn-outline-danger").on('click', function () {
                let id = $(this).attr("value");
                alertify.confirm("Вы действительно хотите удалить эту статью?", function () {
                    $.ajax({
                        type: "delete",
                        url: "{{ route('del.article') }}",
                        data: {_token: "{{ csrf_token() }}", id:id},
                        complete: function () {
                            alertify.success("Статья удалена!");
                            location.reload();
                        }
                    })
                }, function () {
                    alertify.error("Действие отменено");
                });
            })
        });
        // For comment
        $(function(){
            $(".btn-danger").on('click', function () {
                let id = $(this).attr("value");
                alertify.confirm("Вы действительно хотите удалить этот коментарий?", function () {
                    $.ajax({
                        type: "delete",
                        url: "{{ route('del.comment') }}",
                        data: {_token: "{{ csrf_token() }}", id:id},
                        complete: function () {
                            alertify.success("Коментарий удалён!");
                            location.reload();
                        }
                    })
                }, function () {
                    alertify.error("Действие отменено");
                });
            })
        });
    </script>
@endsection