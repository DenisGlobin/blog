<a class="p-2 text-dark" href="{{ route('articles') }}">@lang('menu.articles')</a>
<a class="p-2 text-dark" href="{{ route('user.articles', ['id' => Auth::id()]) }}">@lang('menu.my_articles')</a>
@can('create', App\Article::class)
    <a class="p-2 text-dark" href="{{ route('show.addarticle.form') }}">@lang('menu.new_article')</a>
@endcan
<a class="p-2 text-dark" href="#">@lang('menu.search')</a>