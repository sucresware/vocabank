@extends('layouts/base')

@section('body-classes') @auth {{ auth()->user()->getSetting('layout.theme', 'theme-vocabank') }} @else theme-vocabank @endauth @endsection

@section('body')
    <nav id="header">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <div class="mx-4">
                <a href="{{ route('home') }}">{!! File::get(base_path('/public/svg/logo_white.svg')) !!}</a>
            </div>
            <div class="hidden md:block mx-2">
                <form action="{{ route('samples.search') }}" method="get">
                    <input type="text" placeholder="Recherche" class="form-control form-control-inverse w-64" name="q" value="{{ old('q', $q ?? '') }}">
                    <button type="submit"><i class="nav-link {{ active_class(if_route('samples.search')) }} -ml-10 fa fa-fw fa-search"></i></button>
                </form>
            </div>
            <div class="block md:hidden mx-4">
                <button id="nav-toggle" class="nav-link"><i class="fas fa-bars"></i></button>
            </div>
            <div class="hidden w-full md:block md:w-auto mx-2 md:ml-auto pt-4 md:pt-0" id="nav-content">
                <ul class="flex flex-wrap items-center justify-center md:justify-end">
                    <li class="w-full mx-2 mb-4 md:hidden">
                        <form action="{{ route('samples.search') }}" method="get" class="flex">
                            <div class="flex-auto mr-4">
                                <input type="text" placeholder="Recherche" class="form-control form-control-inverse w-full" name="q" value="{{ old('q', $q ?? '') }}">
                            </div>
                            <button type="submit"><i class="nav-link {{ active_class(if_route('samples.search')) }} fa fa-fw fa-search"></i></button>
                        </form>
                    </li>
                    @guest
                        <li class="mx-2 my-2 md:my-0">
                            <a href="{{ route('register') }}" class="nav-link {{ active_class(if_route('register')) }}">Inscription</a>
                        </li>
                        <li class="mx-2 my-2 md:my-0">
                            <a href="{{ route('login') }}" class="nav-link {{ active_class(if_route('login')) }}">Connexion</a>
                        </li>
                    @else
                        <li class="mx-2">
                            <a href="{{ route('samples.create') }}" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Ajouter</a>
                        </li>
                        <li class="mr-2 ml-auto md:ml-2">
                            <button id="lightSwitch" class="nav-link" title="Changer de thème"><i class="fa fa-fw fa-lightbulb"></i></button>
                        </li>
                        <li class="mx-2">
                            <a href="{{ route('users.show', auth()->user()) }}" class="nav-link {{ active_class(if_route('users.show', auth()->user())) }}" title="Profil"><i class="fas fa-fw fa-user"></i></a>
                        </li>
                        @if (auth()->user()->hasRole('admin'))
                            <li class="mx-2">
                                <a href="{{ route('admin.index') }}" class="nav-link {{ active_class(if_route_pattern('admin.*')) }}" title="Administration"><i class="fas fa-fw fa-lock"></i></a>
                            </li>
                        @endif
                        <li class="mx-2">
                            <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Déconnexion"><i class="fas fa-fw fa-sign-out-alt"></i></a>
                        </li>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container w-full mx-auto flex flex-wrap mb-10" id="app">
        <div class="flex-1 mb-6 px-4 m-w-0">
            @yield('content')
        </div>
        <div class="px-4 w-full md:w-1/4">
            <div class="text-xs text-muted mb-2">
                Tags les plus utilisés :
            </div>

            @foreach($popular_tags as $tag)
                <a href="{{ route('samples.search') }}?q={{ $tag->name }}&tag=✓" class="btn btn-xs btn-secondary mb-1"><i class="fas fa-hashtag"></i>{{ $tag->name }} <span class="text-muted">({{ $tag->count }})</span></a>
            @endforeach

            <hr>

            <footer class="text-xs text-muted">
                <div class="mb-2">
                    @foreach ($static_pages as $static_page)
                        <a href="{{ route('pages', $static_page->slug) }}">{{ $static_page->name }}</a><br>
                    @endforeach
                    <a href="mailto:vocabank@4sucres.org">Contact</a><br>
                    <a href="mailto:vocabank@4sucres.org">Signaler un contenu</a>
                </div>

                <hr>

                <div class="mb-2">
                    VocaBank @version('compact') &copy; 2019<br>
                    Parce qu'on entendait rien sur <a href="https://risibank.fr">RisiBank</a>.<br>
                <hr>
                    Temps d'exécution : {{ round((microtime(true) - LARAVEL_START), 3)*1000 }} ms<br>
                    <a href="https://4sucres.org" target="_blank">4sucres.org</a> — <a href="https://github.com/4sucres/vocabank" target="_blank">GitHub</a>
                </div>
            </footer>
        </div>
    </div>
@endsection
