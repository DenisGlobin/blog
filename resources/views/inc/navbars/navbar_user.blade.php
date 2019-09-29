<a class="p-2 text-dark" href="{{ route('articles') }}">Articles</a>
<a class="p-2 text-dark" href="{{ route('user.article', ['id' => Auth::id()]) }}">My articles</a>
<a class="p-2 text-dark" href="{{ route('show.addarticle.form') }}">Add new article</a>
<a class="p-2 text-dark" href="#">Search</a>