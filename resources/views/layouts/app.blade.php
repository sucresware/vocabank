<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @hasSection ('title')
        @yield('title') - VocaBank
        @else
        VocaBank
        @endif
    </title>
    <meta name="description"
        content="VocaBank - C'est comme RisiBank, sauf qu'on a échangé les stickers par des samples">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ url('/apple-touch-icon-144x144.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ url('/apple-touch-icon-152x152.png') }}">
    <link rel="icon" type="image/png" href="{{ url('/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ url('/favicon-16x16.png') }}" sizes="16x16">
    <meta name="application-name" content="VocaBank">
    <meta name="theme-color" content="#3b4252">
    <meta name="msapplication-TileColor" content="#3b4252">
    <meta name="msapplication-TileImage" content="{{ url('/mstile-144x144.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" />

    <style>
    .animated {
        -webkit-animation-duration: 0.5s;
        animation-duration: 0.5s;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
    }

    .a-delay-1 {
        animation-delay: 100ms;
    }
</style>

    @stack('css')
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <nav id="header" class="">
        <div class="w-full container mx-auto flex flex-wrap items-center mt-0 pt-3 pb-3 md:pb-0">
            <div class="pl-2 md:pl-0">
                <a class="text-gray-900 text-base xl:text-xl no-underline hover:no-underline font-bold" href="#">
                    <img src="/svg/logo.svg" class="h-20">
                </a>
            </div>
        </div>
    </nav>

    <div class="container w-full mx-auto pt-20">
        <div class="w-full px-4 md:px-0 md:mt-8 mb-16 text-gray-800 leading-normal">
            @yield('content')
        </div>
    </div>

    <footer>
        &copy; 2019<br>
        <br>
        <strong>VocaBank (by <a href="https://4sucres.org">4sucres.org</a>)</strong>, parce qu'on entendait rien sur <a
            href="https://risibank.fr">Risibank</a>.<br>
        <a href="{{ route('terms') }}">Conditions générales d'utilisation</a>
    </footer>

    {{--  <script>
        /*Toggle dropdown list*/
        /*https://gist.github.com/slavapas/593e8e50cf4cc16ac972afcbad4f70c8*/

        var userMenuDiv = document.getElementById("userMenu");
        var userMenu = document.getElementById("userButton");

        var navMenuDiv = document.getElementById("nav-content");
        var navMenu = document.getElementById("nav-toggle");

        document.onclick = check;

        function check(e) {
            var target = (e && e.target) || (event && event.srcElement);

            //User Menu
            if (!checkParent(target, userMenuDiv)) {
                // click NOT on the menu
                if (checkParent(target, userMenu)) {
                    // click on the link
                    if (userMenuDiv.classList.contains("invisible")) {
                        userMenuDiv.classList.remove("invisible");
                    } else {
                        userMenuDiv.classList.add("invisible");
                    }
                } else {
                    // click both outside link and outside menu, hide menu
                    userMenuDiv.classList.add("invisible");
                }
            }

            //Nav Menu
            if (!checkParent(target, navMenuDiv)) {
                // click NOT on the menu
                if (checkParent(target, navMenu)) {
                    // click on the link
                    if (navMenuDiv.classList.contains("hidden")) {
                        navMenuDiv.classList.remove("hidden");
                    } else {
                        navMenuDiv.classList.add("hidden");
                    }
                } else {
                    // click both outside link and outside menu, hide menu
                    navMenuDiv.classList.add("hidden");
                }
            }

        }

        function checkParent(t, elm) {
            while (t.parentNode) {
                if (t == elm) {
                    return true;
                }
                t = t.parentNode;
            }
            return false;
        }

    </script>  --}}
</body>


@yield('main')

@if (session('success'))
@php alert()->success(null, session('success'))->persistent(); @endphp
@endif

@if (session('info'))
@php alert()->info(null, session('info'))->persistent(); @endphp
@endif

@if (session('error'))
@php alert()->error(null, session('error'))->persistent(); @endphp
@endif

@include('sweetalert::alert')
{!! GoogleReCaptchaV3::init() !!}
<script src="{{ mix('/js/app.js') }}"></script>
@stack('js')

{{--  <body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
VocaBank
</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
    <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link text-center" href="{{ route('users.index') }}">Membres</a>
        </li>
        @auth
        <li class="nav-item">
            <a class="nav-link text-center" href="{{ route('samples.create') }}"><i class="fas fa-plus"></i> Ajouter</a>
        </li>
        @endauth
    </ul>

    @guest
    <div class="row no-gutters account-block mb-3 mb-md-0">
        <div class="col account-details bg-darker rounded text-md-right text-center text-md-left">
            <a href="{{ route('register') }}" class="mr-1"><i class="fas fa-user-plus"></i> Inscription</a>
            <a href="{{ route('login') }}"><i class="fas fa-power-off"></i> Connexion</a>
        </div>
    </div>
    @else
    <div class="row no-gutters account-block mb-3 mb-md-0">
        <ul class="navbar-nav ml-auto">

        </ul>
        <div class="col account-details bg-darker rounded text-md-right">
            <span class="account-username mr-2">
                <a href="#">{{ auth()->user()->name }}</a>
            </span>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                    class="fas fa-power-off mr-1"></i> Déconnexion</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </div>
    </div>
    @endguest
</div>
</div>
</nav>

<main class="py-4">

</main>


</body>

</html>  --}}
