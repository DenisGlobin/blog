@extends('layouts.app')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-3 mb-4 font-italic border-bottom">@lang('article.articles')</h3>

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
                    <p class="blog-post-meta">Tags:
                        @foreach($article->tags as $tag)
                            <a class="text-dark" href="#">[{{ $tag->name }}], </a>
                        @endforeach
                    </p>
                </div>
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-5">
                            <a href="{{ route('articles') }}">@lang('article.main_page')</a>
                        </div>
                        {{-- Show Delete and Edit buttons for the article's owner --}}
                        @can(['update', 'delete'], $article)
                            <div class="col-3">
                                <a class="btn btn-outline-success btn-sm" href="{{ route('edit.article.form', ['id' => $article->id]) }}" role="button">Edit</a>
                                <button type="button" class="btn btn-outline-danger btn-sm" value="{{ $article->id }}" name="del" id="articleDel">Delete</button>
                            </div>
                        {{-- Show Delete button for Admin --}}
                        @elsecan('delete', $article)
                            <div class="col-3">
                                <button type="button" class="btn btn-outline-danger btn-sm" value="{{ $article->id }}" name="del" id="articleDel">Delete</button>
                            </div>
                        @endcan
                    </div>
                </div>

            </div>
        </div>
        <div class="card flex-md-row mb-4 box-shadow">
            <div class="card-body d-flex flex-column">
                <h2 class="blog-post-title">@lang('comment.title')</h2>
                @auth
                    @include('inc.comments.add_comment_form')
                @endauth
                <br>
                @include('inc.comments.comments')
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Delete article
        $(function () {
            $("button[id^='articleDel']").on('click', function () {
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
        // Delete comment
        $(function () {
            $("button[id^='commentDel']").on('click', function () {
                let id = $(this).attr("value");
                alertify.confirm("@lang('comment.delete')", function () {
                    $.ajax({
                        type: "delete",
                        url: "{{ route('comment.delete') }}",
                        data: {_token: "{{ csrf_token() }}", id:id},
                        complete: function () {
                            alertify.success("@lang('comment.delete_ok')");
                            location.reload();
                        }
                    })
                }, function () {
                    alertify.error("@lang('article.cancel')");
                });
            })
        });
        // Edit comment
        $(function () {
            $("button[id^='commentEdt']").on('click', function () {
                let id = $(this).attr("value");
                let msgElem = $("#commentNum"+id);
                // fill textarea with this comment
                $("textarea#message").val(msgElem.text());
                // Change action url
                $("#commentForm").attr("action", "{{ route('comment.edit') }}");
                // Set comment ID value
                $("input#commentID").val(id);
                // Show button for cancel edition
                $("textarea#message").before("" +
                    "<div class='alert alert-warning alert-dismissible fade show' role='alert' id='cancelEdt'>\n" +
                    "        <strong>Editing comment.</strong>\n" +
                    "        <button type='button' class='close' data-dismiss='alert' aria-label='Close' onclick='editCancel()'>\n" +
                    "            <span aria-hidden='true'>&times;</span>\n" +
                    "        </button>\n" +
                    "</div>");
                // Scrolling page to comment's textarea
                var dn = $("div#cancelEdt").offset().top;
                $('html, body').animate({scrollTop: dn}, 1000);
                alertify.success("@lang('comment.editing')");
            })
        });
        // Cancel editing comment
        function editCancel () {
            // clear textarea
            $("textarea#message").val('');
            // Change action url
            $("#commentForm").attr("action", "{{ route('comment.add') }}");
            alertify.error("@lang('comment.edit_cancel')");
        }
    </script>
@endsection