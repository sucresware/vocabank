@extends('layouts.app')

@section('title')
    Modification du mot de passe - {{ $user->name }}
@endsection

@section('content')

<div class="flex justify-center md:justify-start mb-6">
    <a href="{{ route('users.edit', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-user"></i> Profil</a>
    <a href="{{ route('users.edit.email', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit.email', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-at"></i> Adresse e-mail</a>
    <a href="{{ route('users.edit.password', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit.password', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-key"></i> Mot de passe</a>
</div>

<form action="{{ route('users.update.password', $user) }}" method="post">
    <div class="card p-4 mb-4">
        @method('put')
        @csrf

        @if ($user->password)
            <div class="mb-3">
                <label for="password" class="block text-xs mb-1">Mot de passe actuel<span class="text-red-500">*</span></label>
                <input type="password" name="password" id="password" class="form-control w-full">
                @if ($errors->has('password'))
                    <div class="text-red-500 mb-3 text-xs font-bold">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>
        @else

        @endif

        <div class="mb-3">
            <label for="new_password" class="block text-xs mb-1">Nouveau mot de passe<span class="text-red-500">*</span></label>
            <input type="password" name="new_password" id="new_password" class="form-control w-full">
            @if ($errors->has('new_password'))
                <div class="text-red-500 mb-3 text-xs font-bold">
                    {{ $errors->first('new_password') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
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
        <button class="btn btn-primary">
            Valider
        </button>
    </div>
</form>

@endsection
