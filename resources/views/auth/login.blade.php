@extends('layouts.base')

@section('title')
    Connexion
@endsection

@section('body-classes', 'theme-vocabank w-full h-screen')

@section('body')
<div class="flex w-full h-screen items-center justify-center">
    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w:1/6 text-center mx-4">
        <img src="/svg/logo_white.svg" class="mx-auto mb-6 w-48 animated fadeInDown">

        <div class="mb-6">
            <a href="/login/4sucres" class="btn btn-primary">
                <img src="/img/4sucres.png" class="inline h-6 mr-1" alt="4sucres">
                Connexion avec 4sucres
            </a>
        </div>

        <div class="flex justify-center items-center text-center mb-6">
            <hr class="flex-1">
            <div class="mx-4">ou</div>
            <hr class="flex-1">
        </div>

        <form action="{{ route('login') }}" method="post">
            @csrf

            <div class="card mb-4 p-4">
                <div class="mb-3">
                    <input type="text" placeholder="Adresse e-mail" class="form-control w-full" name="email" value="{{ old('email') }}">

                    @if ($errors->has('email'))
                        <div class="text-red-500 mt-3 text-xs font-bold">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <div>
                    <input type="password" placeholder="Mot de passe" class="form-control w-full" name="password">

                    @if ($errors->has('password'))
                        <div class="text-red-500 text-xs mt-3 font-bold">
                            <strong>{{ $errors->first('password') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-secondary"><i class="fa fa-sign-in-alt mr-1"></i> Connexion</button>
            </div>
        </form>

        <div class="mt-6 text-xs text-muted">
            <a href="{{ route('register') }}"> Inscription</a> —
            <a href="{{ route('register') }}"> Mot de passe oublié</a>
        </div>
    </div>
</div>
@endsection