@if(! $comments->isEmpty())
    @foreach($comments as $comment)
        <div class="p-3 mb-3 bg-light rounded">
            <p class="blog-post-meta">
                {!! $comment->created_at !!} by
                <a href="{{ route('user.info', ['id' => $comment->user->id]) }}">{{ $comment->user->name }}</a>
            </p>
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