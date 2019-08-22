@extends('layouts.base')

@section('title', 'Redéfinir mon mot de passe')
@section('body-classes', 'theme-vocabank w-full h-screen')

@section('body')
<div class="flex w-full h-screen items-center justify-center">
    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w:1/6 text-center mx-4">
        <img src="/svg/logo_white.svg" class="mx-auto mb-6 w-48 animated fadeInDown">

        <form method="POST" action="{{ route('password.update', $token) }}">
            @csrf

            <div class="card mb-4 p-4">
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                        <label for="new_password" class="block text-xs mb-1">Nouveau mot de passe<span class="text-red-500">*</span></label>
                        <input type="password" name="new_password" id="new_password" class="form-control w-full">
                        @if ($errors->has('new_password'))
                            <div class="text-red-500 mb-3 text-xs font-bold">
                                {{ $errors->first('new_password') }}
                            </div>
                        @endif
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-xs mb-1">Nouveau mot de passe (confirmation)<span class="text-red-500">*</span></label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control w-full">
                    @if ($errors->has('new_password_confirmation'))
                        <div class="text-red-500 mb-3 text-xs font-bold">
                            {{ $errors->first('new_password_confirmation') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-secondary"><i class="fa fa-sign-in-alt mr-1"></i> Je vais m'en souvenir</button>
            </div>

        </form>
    </div>
</div>
@endsection
