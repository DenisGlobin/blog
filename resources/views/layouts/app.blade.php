<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
     <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/blog.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>

    <!-- Top navigation panel -->
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow" id="top">
        <h5 class="my-0 mr-md-auto font-weight-normal">
        <a class="blog-header-logo text-dark" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        </h5>
        <nav class="my-2 my-md-0 mr-md-3">
            @auth
                @if (Auth::user()->is_admin)
                    @include('inc.navbars.navbar_admin')
                @else
                    @include('inc.navbars.navbar_user')
                @endif
            @endauth
            @guest
                @include('inc.navbars.navbar_guest')
            @endguest
        </nav>

        @guest
            <a class="btn btn-light" href="{{ route('login') }}">{{ __('Login') }}</a>
            @if (Route::has('register'))
                <a class="btn btn-light" href="{{ route('register') }}">{{ __('Register') }}</a>
            @endif
        @else
            <a id="navbarDropdown" class="btn btn-light dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="btn btn-light dropdown-item" href="{{ route('profile') }}">@lang('profile.title')</a>
                <a class="btn btn-light dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        @endguest
    </div>
    <!-- End top navigation panel-->

        <main role="main" class="container">
            <div class="row">

                <!-- Main content -->
                @yield('content')
                <!-- End main content -->

                <!-- Right sidebar -->
                <aside class="col-md-4 blog-sidebar">
                    <a href="{{ route('set.locale', ['locale' => 'en']) }}">En</a> |
                    <a href="{{ route('set.locale', ['locale' => 'ru']) }}">Ru</a>
                    @hasSection('tags')
                        @yield('tags')
                    @endif

                    @hasSection('archive')
                        @yield('archive')
                    @endif

                </aside>
                <!-- End right sidebar -->

            </div>
        </main>

    <footer class="blog-footer">
        <p>Blog created by <a href="https://github.com/DenisGlobin?tab=repositories">Denis Globin</a>.</p>
        <p>
            <a href="#" onclick="scrollingPageToTop()">Back to top</a>
        </p>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <!-- Alerts notification -->
    @include('inc.alerts')
    @include('inc.scroll_to_top')
    @yield('js')
</body>
</html>
