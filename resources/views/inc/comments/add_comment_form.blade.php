@if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
@endif
<form action="{{ route('add.comment') }}" method="post">
    @csrf
    <input id="userID" name="userID" type="hidden" value="{{ isset($article->user->id) ? $article->user->id : null }}">
    <input id="articleID" name="articleID" type="hidden" value="{{ isset($article->id) ? $article->id : null }}">
    <div class="form-group">
        <label for="message">Type your message</label>
        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5"></textarea>
        @error('message')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Send</button>
</form>