@extends('layouts.base')

@section('title', 'Mot de passe oublié')
@section('body-classes', 'theme-vocabank w-full h-screen')

@section('body')
<div class="flex w-full h-screen items-center justify-center">
    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w:1/6 text-center mx-4">
        <img src="/svg/logo_white.svg" class="mx-auto mb-6 w-48 animated fadeInDown">

        <form action="{{ route('password.email') }}" method="post">
            @csrf

            <div class="card mb-4 p-4">
                <div>
                    <input type="text" placeholder="Adresse e-mail" class="form-control w-full" name="email" value="{{ old('email') }}">

                    @if ($errors->has('email'))
                        <div class="text-red-500 mt-3 text-xs font-bold">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-secondary"><i class="fa fa-sign-in-alt mr-1"></i> Bah alors, on est rouillé ?</button>
            </div>
        </form>
    </div>
</div>
@endsection
