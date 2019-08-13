@extends('layouts/base')

@section('body-classes')
    @auth
        {{ auth()->user()->getSetting('layout.theme', 'theme-vocabank') }}
    @else
        theme-vocabank
    @endauth
@endsection

@section('body')
    <div class="font-bold text-xs p-3 py-1 text-center text-red-500 shadow-lg">VocaBank v2 - Open Alpha - La base de données peut être remise à zéro à tout moment.</div>

    <nav id="header">
        <div class="container mx-auto flex flex-wrap items-center">
            <div class="mx-2">
                <a href="{{ route('home') }}">{!! File::get(base_path('/public/svg/logo_white.svg')) !!}</a>
            </div>
            <div class="mx-2">
                <form action="{{ route('samples.search') }}" method="get">
                    <input type="text" placeholder="Recherche" class="form-control form-control-inverse" name="q" value="{{ old('q', $q ?? '') }}">
                    <button type="submit"><i class="nav-link {{ active_class(if_route('samples.search')) }} -ml-8 fa fa-search"></i></button>
                </form>
            </div>
            <div class="mx-2 ml-auto">
                <div class="flex flex-wrap items-center justify-end">
                    @guest
                        <div class="px-3"><a href="{{ route('register') }}" class="nav-link {{ active_class(if_route('register')) }}">Inscription</a></div>
                        <div class="pl-3"><a href="{{ route('login') }}" class="nav-link {{ active_class(if_route('login')) }}">Connexion</a></div>
                    @else
                        <a href="{{ route('samples.create') }}" class="mx-3 btn btn-primary"><i class="fa fa-plus"></i> Ajouter</a>
                        <a href="{{ route('users.show', auth()->user()) }}" class="mx-3 nav-link {{ active_class(if_route('users.show', auth()->user())) }}"><i class="fas fa-user"></i></a>
                        <a href="#" class="mx-3 nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i></a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    @yield('main')

    <div class="container w-full mx-auto" id="app">
        @yield('content')
    </div>

    <footer class="container w-full mx-auto text-xs text-muted text-center my-8">
        <div class="mb-2"><img src="/svg/logo.svg" class="h-3 inline align-text-bottom"> &copy; 2019</div>

        <strong>VocaBank</strong>, parce qu'on entendait rien sur <a href="https://risibank.fr">RisiBank</a>.<br>

        <a href="javascript:void(0)" id="lightSwitch"><i class="fas fa-lightbulb"></i></a> —
        <a href="{{ route('api') }}">API</a> —
        <a href="{{ route('terms') }}">Conditions générales d'utilisation</a> —
        <a href="https://github.com/4sucres/vocabank" target="_blank">GitHub</a><br>

        Partenaires :
        <a href="https://4sucres.org" target="_blank">4sucres.org</a> —
        <a href="https://olinux.org" target="_blank">Olinux Records®</a><br>
    </footer>
@endsection
