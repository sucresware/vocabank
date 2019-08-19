@extends('layouts.app')

@section('title')
    Modification de l'adresse e-mail - {{ $user->name }}
@endsection

@section('content')

<div class="flex mb-6">
    <a href="{{ route('users.edit', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-user"></i> Profil</a>
    <a href="{{ route('users.edit.email', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit.email', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-at"></i> Adresse e-mail</a>
    <a href="{{ route('users.edit.password', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit.password', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-key"></i> Mot de passe</a>
</div>


<form action="{{ route('users.update.email', $user) }}" method="post">
    <div class="card p-4 mb-4">
        @method('put')
        @csrf

        <div class="mb-3">
            <label for="name" class="block text-xs mb-1">Email<span class="text-red-500">*</span></label>
            <input type="text" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control w-full">
            @if ($errors->has('email'))
                <div class="text-red-500 mb-3 text-xs font-bold">
                    {{ $errors->first('email') }}
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
