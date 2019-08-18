@if(! $comments->isEmpty())
    @foreach($comments as $comment)
        <div class="blog-post">
            <p class="blog-post-meta">
                {!! $comment->created_at !!} by
                <a href="{{ route('user.info', ['id' => $comment->user->id]) }}">{{ $comment->user->name }}</a>
            </p>

            <p>{!! $comment->message !!}</p>
            <hr/>
        </div>
    @endforeach

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
@else
    <span>There are no comments yet.</span>
@endif