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
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
        <h5 class="my-0 mr-md-auto font-weight-normal">
        <a class="blog-header-logo text-dark" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        </h5>
        <nav class="my-2 my-md-0 mr-md-3">
            @auth
                <a class="p-2 text-dark" href="{{ route('articles') }}">Articles</a>
                <a class="p-2 text-dark" href="{{ route('profile') }}">Profile</a>
                <a class="p-2 text-dark" href="#">Add new article</a>
                <a class="p-2 text-dark" href="#">Search</a>
            @endauth
            @guest
                <a class="p-2 text-dark" href="{{ route('articles') }}">Articles</a>
                <a class="p-2 text-dark" href="#">Search</a>
            @endguest
        </nav>

        @guest
            <a class="btn btn-outline-primary" href="{{ route('login') }}">{{ __('Login') }}</a>
            @if (Route::has('register'))
                <a class="btn btn-outline-primary" href="{{ route('register') }}">{{ __('Register') }}</a>
            @endif
        @else
            <a id="navbarDropdown" class="btn btn-outline-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="btn btn-outline-primary dropdown-item" href="{{ route('logout') }}"
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
                    <div class="p-3 mb-3 bg-light rounded">
                        <h4 class="font-italic">Tags</h4>
                        <p class="mb-0">
                            <a class="text-dark" href="#">Tag1</a>,
                            <a class="text-dark" href="#">Tag2</a>,
                            <a class="text-dark" href="#">Tag3</a>,
                            <a class="text-dark" href="#">Tag4</a>,
                        </p>
                    </div>

                    <div class="p-3">
                        <h4 class="font-italic">Archives</h4>
                        <ol class="list-unstyled mb-0">
                            <li><a href="#">March 2014</a></li>
                            <li><a href="#">February 2014</a></li>
                            <li><a href="#">January 2014</a></li>
                            <li><a href="#">December 2013</a></li>
                            <li><a href="#">November 2013</a></li>
                            <li><a href="#">October 2013</a></li>
                            <li><a href="#">September 2013</a></li>
                            <li><a href="#">August 2013</a></li>
                            <li><a href="#">July 2013</a></li>
                            <li><a href="#">June 2013</a></li>
                            <li><a href="#">May 2013</a></li>
                            <li><a href="#">April 2013</a></li>
                        </ol>
                    </div>
                </aside>
                <!-- End right sidebar -->

            </div>

        </main>

    <footer class="blog-footer">
        <p>Blog created by <a href="https://github.com/DenisGlobin?tab=repositories">Denis Globin</a>.</p>
        <p>
            <a href="#">Back to top</a>
        </p>
    </footer>
    <script src="/js/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="/js/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
