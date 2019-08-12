@extends('layouts/base')

@section('body-classes' , 'bg-gray-100 text-gray-700 text-sm')

@section('body')
    <nav id="header" class="bg-gray-800 w-full py-3 shadow-lg">
        <div class="container mx-auto flex flex-wrap items-center">
            <div class="mx-2">
                <div class="flex items-center">
                    <a class="text-gray-900 text-base xl:text-xl no-underline hover:no-underline font-bold" href="{{ route('home') }}">
                        {!! File::get(base_path('/public/svg/logo_white.svg')) !!}
                    </a>
                </div>
            </div>
            <div class="mx-2">
                <form action="{{ route('samples.search') }}" method="get">
                    <input type="text" placeholder="Recherche" class="bg-gray-800 py-1 text-white border-b-2 border-teal-600" name="q" value="{{ old('q', $q ?? '') }}">
                    <button type="submit"><i class="{{ active_class(if_route('samples.search'), 'text-white font-bold', 'text-gray-600 hover:text-gray-400') }} -ml-6 fa fa-search"></i></button>
                </form>
            </div>
            <div class="mx-2 ml-auto">
                <div class="flex flex-wrap items-center justify-end">
                    @guest
                        <div class="px-3"><a href="{{ route('register') }}" class="{{ active_class(if_route('register'), 'text-white font-bold', 'text-gray-600 hover:text-gray-400') }}">Inscription</a></div>
                        <div class="pl-3"><a href="{{ route('login') }}" class="{{ active_class(if_route('login'), 'text-white font-bold', 'text-gray-600 hover:text-gray-400') }}">Connexion</a></div>
                    @else
                        <a href="{{ route('samples.create') }}" class="mx-3 px-3 py-1 font-bold rounded-full text-white bg-teal-500 hover:bg-teal-600"><i class="fa fa-plus"></i> Ajouter</a>
                        <a href="{{ route('users.show', auth()->user()) }}" class="mx-3 {{ active_class(if_route('users.show', auth()->user()) , 'text-white', 'text-gray-600 hover:text-gray-400') }}">{{ auth()->user()->name }}</a>
                        <a href="#" class="mx-3 text-gray-600 hover:text-gray-400" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    @yield('main')

    <div class="container w-full mx-auto pt-8" id="app">
        @yield('content')
    </div>

    <footer class="container w-full mx-auto text-gray-500 text-xs text-center my-8">
        <div class="mb-2"><img src="/svg/logo.svg" class="h-3 inline align-text-bottom"> &copy; 2019</div>

        <strong>VocaBank</strong>, parce qu'on entendait rien sur <a href="https://risibank.fr" class="text-gray-600 hover:text-gray-500"">RisiBank</a>.
        <br>

        <a class="text-gray-600 hover:text-gray-500" href="{{ route('api') }}">API</a> —
        <a class="text-gray-600 hover:text-gray-500" href="{{ route('terms') }}">Conditions générales d'utilisation</a> —
        <a class="text-gray-600 hover:text-gray-500" href="https://github.com/4sucres/vocabank" target="_blank">GitHub</a>
        <br>

        Partenaires :
        <a class="text-gray-600 hover:text-gray-500" href="https://4sucres.org" target="_blank">4sucres.org</a> —
        <a class="text-gray-600 hover:text-gray-500" href="https://olinux.org" target="_blank">Olinux Records®</a>
        <br>
    </footer>

@endsection
