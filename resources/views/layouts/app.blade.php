@extends('layouts/base')

@section('body-classes' , 'bg-gray-100 text-gray-700 text-sm')

@section('body')
    <nav id="header" class="bg-gray-800 w-full py-3 shadow-lg">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <div class="pl-2 md:pl-0 w-1/4">
                <div class="flex items-center">
                    <a class="text-gray-900 text-base xl:text-xl no-underline hover:no-underline font-bold" href="{{ route('home') }}">
                        {!! File::get(base_path('/public/svg/logo_white.svg')) !!}
                    </a>
                    <div class="ml-3">
                        <a href="{{ route('samples.create') }}" class="inline-block px-3 py-1 font-bold rounded-full bg-teal-500 text-white hover:bg-gray-600">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="px-2">
                <form action="{{ route('samples.search') }}" method="get">
                    <input type="text" placeholder="Recherche" class="bg-gray-700 rounded-full px-4 py-1 text-white" name="q" value="{{ old('q', $q ?? '') }}">
                    <button type="submit"><i class="{{ active_class(if_route('samples.search'), 'text-white font-bold', 'text-gray-600 hover:text-gray-400') }} -ml-10 fa fa-search"></i></button>
                </form>
            </div>
            <div class="pr-2 md:pr-0 w-1/4">
                <div class="flex flex-wrap justify-end">
                    @guest
                        <div class="px-3"><a href="{{ route('register') }}" class="{{ active_class(if_route('register'), 'text-white font-bold', 'text-gray-600 hover:text-gray-400') }}">Inscription</a></div>
                        <div class="pl-3"><a href="{{ route('login') }}" class="{{ active_class(if_route('login'), 'text-white font-bold', 'text-gray-600 hover:text-gray-400') }}">Connexion</a></div>
                    @else
                        <div class="px-3"><a href="{{ route('users.show', auth()->user()) }}" class="{{ active_class(if_route('users.show', auth()->user()) , 'text-white font-bold', 'text-gray-600 hover:text-gray-400') }}">{{ auth()->user()->name }}</a></div>
                        <div class="pl-3"><a href="#" class="text-gray-600 hover:text-gray-400" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a></div>
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
        <strong>VocaBank</strong>, parce qu'on entendait rien sur <a
            href="https://risibank.fr">RisiBank</a>.<br>
            Partenaires : <a class="text-gray-600 hover:text-gray-500" href="#">4sucres.org</a> — <a class="text-gray-600 hover:text-gray-500" href="#">Olinux Records®</a><br>
        <a class="text-gray-600 hover:text-gray-500" href="#">API</a> — <a class="text-gray-600 hover:text-gray-500" href="{{ route('terms') }}">Conditions générales d'utilisation</a>
    </footer>

@endsection
