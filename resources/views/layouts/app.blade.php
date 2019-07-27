@extends('layouts/base')

@section('body')
<body class="bg-gray-100 text-gray-700 text-sm">
    <nav id="header" class="bg-gray-800 w-full py-3 shadow-lg">
        <div class="container mx-auto flex flex-wrap items-center">
            <div class="flex-1 pl-2 md:pl-0">
                <div class="mb-3">
                    <a class="text-gray-900 text-base xl:text-xl no-underline hover:no-underline font-bold" href="{{ route('home') }}" class="h-8">
                        {{-- <img src="" id="logo" > --}}
                        {!! File::get(base_path('/public/svg/logo_white.svg')) !!}
                    </a>
                </div>
                <div class="container mx-auto flex flex-wrap">
                    <div class="pr-3"><a href="#" class="text-white font-bold">Explorer</a></div>
                    <div class="px-3"><a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-400">Membres</a></div>
                    @guest
                        <div class="px-3"><a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-400">Inscription</a></div>
                        <div class="pl-3"><a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-400">Connexion</a></div>
                    @else
                        <div class="px-3"><a href="{{ route('samples.create') }}" class="text-gray-600 hover:text-gray-400">Ajouter</a></div>
                        <div class="px-3"><a href="{{ route('users.show', auth()->user()) }}" class="text-gray-600 hover:text-gray-400">Profil</a></div>
                        <div class="px-3"><a href="#" class="text-gray-600 hover:text-gray-400">Favoris</a></div>
                        <div class="pl-3"><a href="#" class="text-gray-600 hover:text-gray-400" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a></div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    @endguest
                </div>
            </div>
            <div class="pr-2 md:pr-0 w:1/6">
                <form action="{{ route('samples.search') }}" method="get">
                    <input type="text" placeholder="Recherche" class="bg-gray-700 rounded-full px-4 py-1 text-white" name="q" value="{{ old('q', $q ?? '') }}">
                    <button type="submit"><i class="text-gray-600 hover:text-gray-400 -ml-10 fa fa-search"></i></button>
                </form>
            </div>
        </div>
    </nav>

    @yield('main')

    <div class="container w-full mx-auto pt-8" id="app">
        @yield('content')
    </div>

    <footer class="container w-full mx-auto text-gray-500 text-xs text-center mt-8">
        <div class="mb-2"><img src="/svg/logo.svg" class="h-3 inline align-text-bottom"> &copy; 2019</div>
        <strong>VocaBank</strong>, parce qu'on entendait rien sur <a
            href="https://risibank.fr">RisiBank</a>.<br>
            Partenaires : <a class="text-gray-600 hover:text-gray-500" href="#">4sucres.org</a> — <a class="text-gray-600 hover:text-gray-500" href="#">Olinux Records®</a><br>
        <a class="text-gray-600 hover:text-gray-500" href="#">API</a> — <a class="text-gray-600 hover:text-gray-500" href="{{ route('terms') }}">Conditions générales d'utilisation</a>
    </footer>

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
</body>
@endsection
