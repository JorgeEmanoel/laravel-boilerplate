<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/select2.min.css') }}" rel="stylesheet"/>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel Boilerplate') }}</a>
                </div>
                @if (Auth::guest())
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    </ul>
                @else
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">{{ Auth::user()->name }}
                                <span class="caret"></span>
                            </a>
                            <form hidden action="{{ route('logout') }}" method="post" id="form-logout">
                                {{ csrf_field() }}
                            </form>
                            <ul class="dropdown-menu">
                                <li><a href="#" onclick="document.getElementById('form-logout').submit()">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                @endif
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>
        $('.select2').select2();
    </script>
    @yield('scripts')
</body>
</html>
