@extends('layouts.base')

@section('title')
    Connexion
@endsection

@section('body-classes', 'bg-gray-800 text-gray-200 w-full h-screen text-sm')

@section('body')
<div class="flex w-full h-screen items-center justify-center">
    <div class="w-1/6 text-center">
        <img src="/svg/logo_white.svg" class="mx-auto mb-6 w-48 animated fadeInDown">

        <a href="/login/4sucres" class="hover:bg-gray-600 bg-gray-700 text-white text-center px-4 py-2 font-bold rounded-full mb-6 inline-block">
            <img src="/img/4sucres.png" class="inline h-6 mr-1" alt="">
            Connexion avec 4sucres
        </a>

        <div class="text-center mb-6 mt-2">
            <div class="bg-gray-700" style="height: 1px;"></div>
            <div class="-mt-3">
                <span class="bg-gray-800 px-2">ou</span>
            </div>
        </div>

        <form action="{{ route('login') }}" method="post" class="">
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
                <button type="submit" class="hover:bg-gray-600 bg-gray-700 text-white text-center px-3 py-1 font-bold rounded-full"><i class="fa fa-sign-in-alt mr-1"></i> Connexion</button>
            </div>
        </form>

        <div class="mt-6 text-xs text-gray-500">
            <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-400"> Inscription</a> —
            <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-400"> Mot de passe oublié</a>
        </div>
    </div>
</div>
@endsection