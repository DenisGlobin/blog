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
                    @if($comment->user->id == Auth::id())
                        <div class="col-3">
                            <a class="btn btn-primary btn-sm" href="#" role="button">Edit</a>
                            <button type="button" class="btn btn-danger btn-sm" value="{{ $comment->id }}" name="del">Delete</button>
                        </div>
                    @endif
                </div>
            </div>
            <hr/>
            <p class="mb-0">{!! $comment->message !!}</p>
        </div>
    @endforeach

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
@else
    <span>{{ __('article.no_comments') }}</span>
@endif