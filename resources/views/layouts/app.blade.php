@extends('layouts/base')

@section('body-classes')
    @auth
        {{ auth()->user()->getSetting('layout.theme', 'theme-vocabank') }}
    @else
        theme-vocabank
    @endauth
@endsection

@section('body')
    <nav id="header">
        <div class="container mx-auto flex flex-wrap items-center">
            <div class="mx-2">
                <a href="{{ route('home') }}">{!! File::get(base_path('/public/svg/logo_white.svg')) !!}</a>
            </div>
            <div class="mx-2">
                <form action="{{ route('samples.search') }}" method="get">
                    <input type="text" placeholder="Recherche" class="form-control form-control-inverse w-48" name="q" value="{{ old('q', $q ?? '') }}">
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

    <div class="container w-full mx-auto flex mb-10" id="app">
        <div class="px-3 flex-1">
            @yield('content')
        </div>
        <div class="px-3 w-1/4">
            <div class="card text-red-500 px-5 py-3 mb-6">
                <span class="font-bold uppercase">VocaBank v2 - Open Alpha</span><br>
                <div class="text-xs">
                    &bull; La base de données peut être remise à zéro à tout moment<br>
                    &bull; Certaines fonctionnalités ne fonctionnent pas encore<br>
                    &bull; Open for hackers (cc ptdr)
                </div>
            </div>
            <hr>

            <div class="text-xs text-muted mb-2">
                Tags les plus utilisés :
            </div>

            @foreach($popular_tags as $tag)
                <a href="{{ route('samples.search') }}?q={{ $tag->name }}&tag=✓" class="btn btn-xs btn-secondary mb-1"><i class="fas fa-hashtag"></i>{{ $tag->name }} <span class="text-muted">({{ $tag->count }})</span></a>
            @endforeach

            <hr>

            <footer class="text-xs text-muted">
                <div class="mb-2">
                    VocaBank &copy; 2019<br>
                    Parce qu'on entendait rien sur <a href="https://risibank.fr">RisiBank</a>.<br>
                    Temps d'exécution : {{ round((microtime(true) - LARAVEL_START), 3)*1000 }} ms<br>
                </div>

                <hr>

                <div class="mb-2">
                    Liens :<br>
                    <a href="javascript:void(0)" id="lightSwitch">Changement de thème</a><br>
                    <a href="{{ route('terms') }}">Conditions générales d'utilisation</a><br>
                    <a href="{{ route('api') }}">API</a><br>
                    <a href="https://github.com/4sucres/vocabank" target="_blank">GitHub</a><br>
                </div>

                <hr>

                <div class="mb-2">
                    Partenaires :<br>
                    <a href="https://4sucres.org" target="_blank">4sucres.org</a><br>
                    <a href="https://olinux.org" target="_blank">Olinux Records®</a><br>
                </div>
            </footer>
        </div>
    </div>
@endsection
