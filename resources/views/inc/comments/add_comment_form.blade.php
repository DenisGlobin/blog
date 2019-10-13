<form action="{{ route('comment.add') }}" method="post" id="commentForm">
    @csrf
    <input id="articleID" name="articleID" type="hidden" value="{{ isset($article->id) ? $article->id : null }}">
    <input id="commentID" name="commentID" type="hidden" value="">
    <div class="form-group">
        <label for="message">@lang('article.type_msg')</label>
        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5"></textarea>
        @error('message')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Send</button>
</form>
