@extends('layouts.app')

@section('title')
    Suppression du compte - {{ $user->name }}
@endsection

@section('content')

<div class="flex justify-center mb-6 md:justify-start">
    <a href="{{ route('users.edit', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-user"></i> Profil</a>
    <a href="{{ route('users.edit.email', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit.email', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-at"></i> Adresse e-mail</a>
    <a href="{{ route('users.edit.password', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit.password', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-key"></i> Mot de passe</a>
</div>

<form action="{{ route('users.destroy', $user) }}" method="post">
    <div class="p-4 mb-4 card">
        @method('delete')
        @csrf

        <div class="mb-3">
            <p>
                <i class="text-red-500 fas fa-fw fa-exclamation-triangle"></i> T'es sûr de vouloir supprimer ton compte ? Cette action est irréversible. Pour confirmer, entre ton mot de passe.
            </p>
        </div>

        <div class="mb-3">
            <div class="mb-3">
                <label for="password" class="block mb-1 text-xs">Mot de passe actuel<span class="text-red-500">*</span></label>
                <input type="password" name="password" id="password" class="w-full form-control">
                @if ($errors->has('password'))
                    <div class="mb-3 text-xs font-bold text-red-500">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div>
        <div class="text-right">
            <button class="btn btn-primary">
                <i class="fas fa-fw fa-trash"></i> Je suis un lâche
            </button>
        </div>
    </div>
</form>

@endsection
