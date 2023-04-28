<!doctype html>
@routes
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>{{ config('app.name', 'Bikerz') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>   -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.css"  />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js" ></script>

    <style>
        .active_menu{
            background:#b60505;
        }

        .not_active_menu:hover{
            background:#fc08087d;
            transition: all 0.3s;
        }

        .navbar-nav{
            border-bottom: 1px solid #b60505
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @yield('head')
</head>
<body>
    <div id="app">
    <nav class="navbar navbar-expand-md navbar-dark bg-black shadow-sm">
    <div class="container">
        <div class="row w-100">
            <div class="col-12 d-flex justify-content-center">
                <a class="navbar-brand d-flex justify-content-center m-0" href="{{ route('frontend.index') }}">
                    <img class="w-50" src="{{ asset('images/logo-bikerz.png') }}" alt="logo">
                </a>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                    <ul class="navbar-nav w-50">
                        <li class="nav-item {{Route::currentRouteName() == 'frontend.index'?'active_menu':'not_active_menu'}}">
                            <a class="nav-link" href="{{route('frontend.index')}}">Inici</a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName() == 'frontend.inscripcions'?'active_menu':'not_active_menu'}}">
                            <a class="nav-link" href="{{route('frontend.inscripcions')}}">Inscripcions</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav w-50 justify-content-end">
                        <li class="nav-item {{Route::currentRouteName() == 'corredors.formulariAfegir'?'active_menu':'not_active_menu'}}">
                            <a class="nav-link" href="{{route('corredors.formulariAfegir')}}">Registrar-se</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

        <main class="py-4 p-2">
            @yield('content')
        </main>
    </div>
</body>
</html>
