<a class="p-2 text-dark" href="{{ route('articles') }}">Articles</a>
<a class="p-2 text-dark" href="{{ route('user.articles', ['id' => Auth::id()]) }}">My articles</a>
@can('create', App\Article::class)
    <a class="p-2 text-dark" href="{{ route('show.addarticle.form') }}">Add new article</a>
@endcan
<a class="p-2 text-dark" href="#">Search</a>