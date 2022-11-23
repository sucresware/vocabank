@extends('layouts.base')

@section('title')
    Inscription
@endsection

@section('body-classes', 'theme-vocabank w-full h-screen')

@section('body')
<div class="flex items-center justify-center w-full h-screen">
    <div class="w-full mx-4 text-center sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w:1/6">
        <img src="/svg/logo_white.svg" class="w-48 mx-auto mb-6 animated fadeInDown">

        <form action="{{ route('register') }}" method="post">
            @csrf

            <div class="p-4 mb-4 card">
                <div class="mb-3">
                    <input type="text" placeholder="Pseudo" class="w-full form-control" name="name" value="{{ old('name') }}">

                    @if ($errors->has('name'))
                        <div class="mt-3 text-xs font-bold text-red-500">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <input type="text" placeholder="Adresse e-mail" class="w-full form-control" name="email" value="{{ old('email') }}">

                    @if ($errors->has('email'))
                        <div class="mt-3 text-xs font-bold text-red-500">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <div>
                    <input type="password" placeholder="Mot de passe" class="w-full form-control" name="password">

                    @if ($errors->has('password'))
                        <div class="mt-3 text-xs font-bold text-red-500">
                            <strong>{{ $errors->first('password') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-secondary"><i class="mr-1 fa fa-sign-in-alt"></i> Créer un compte</button>
            </div>
        </form>

        <div class="mt-6 text-xs text-muted">
            <a href="{{ route('login') }}"> J'ai déjà un compte</a>
        </div>
    </div>
</div>
@endsection