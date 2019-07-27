@extends('layouts.base')

@section('title')
    Connexion
@endsection

@section('body')
<body class="bg-gray-800 text-gray-200 w-full h-screen text-sm">
    <div class="flex w-full h-screen items-center justify-center">
        <div class="w-1/6">
            <img src="/svg/logo_white.svg" class="mx-auto mb-6 w-48">
        </div>
        <div class="w-1/6">
            <div class="flex text-center mb-5">
                <div class="w-1/2 pb-3 text-white font-bold border-teal-500" style="border-bottom-width: 2px;">
                    Connexion
                </div>
                <a href="{{ route('register') }}" class="w-1/2 pb-3 text-gray-600 hover:text-gray-400">
                    Inscription
                </a>
            </div>


            <form action="{{ route('login') }}" method="post">
                @csrf

                <input type="text" placeholder="Adresse e-mail" class="bg-gray-700 rounded px-2 py-1 text-white mb-3 block w-full" name="email" value="{{ old('email') }}">

                @if ($errors->has('email'))
                    <div class="text-red-500 mb-3 text-xs font-bold">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                <input type="password" placeholder="Mot de passe" class="bg-gray-700 rounded px-2 py-1 text-white mb-3 block w-full" name="password">

                @if ($errors->has('password'))
                    <div class="text-red-500 mb-3 text-xs font-bold">
                        <strong>{{ $errors->first('password') }}
                    </div>
                @endif

                <div class="text-right">
                    <button type="submit" class="hover:bg-gray-700 text-white text-center px-3 py-1 font-bold rounded-full"><i class="fa fa-sign-in-alt mr-1"></i> Connexion</button>
                </div>
            </form>
        </div>
    </div>
</body>
@endsection