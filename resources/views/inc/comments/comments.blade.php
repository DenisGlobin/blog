@if(! $comments->isEmpty())
    @foreach($comments as $comment)
        <div class="p-3 mb-3 bg-light rounded">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-5">
                        <p class="blog-post-meta">
                            {!! $comment->created_at !!} by
                            <a href="{{ route('user.info', ['id' => $comment->user->id]) }}">{{ $comment->user->name }}</a>
                        </p>
                    </div>
                    {{-- Show Delete and Edit buttons for the article's owner --}}
                    @can(['update', 'delete'], $comment)
                        <div class="col-3">
                            <button type="button" class="btn btn-outline-success btn-sm" value="{{ $comment->id }}"
                                    name="edit" id="commentEdt{{ $comment->id }}">Edit</button>
                            <button type="button" class="btn btn-outline-danger btn-sm" value="{{ $comment->id }}"
                                    name="del" id="commentDel{{ $comment->id }}">Delete</button>
                        </div>
                        {{-- Show Delete button for Admin --}}
                    @elsecan('delete', $article)
                        <div class="col-3">
                            <button type="button" class="btn btn-outline-danger btn-sm" value="{{ $comment->id }}"
                                    name="del" id="commentDel{{ $comment->id }}">Delete</button>
                        </div>
                    @endcan
                </div>
            </div>
            <hr/>
            <p class="mb-0" id="commentNum{{ $comment->id }}">{!! $comment->message !!}</p>
        </div>
    @endforeach

@else
    <span>@lang('article.no_comments')</span>
@endif