@extends('layouts.app')

@section('title')
    Modification de l'adresse e-mail - {{ $user->name }}
@endsection

@section('content')

<div class="flex flex-wrap justify-center mx-2">
    <div class="w-1/2 mx-2">
        <div class="flex justify-center mb-6">
            <a href="{{ route('users.edit', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit', $user), 'btn-primary', 'btn-tertiary') }}"><i class="fas fa-fw fa-user"></i> Profil</a>
            <a href="{{ route('users.edit.email', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit.email', $user), 'btn-primary', 'btn-tertiary') }}"><i class="fas fa-fw fa-at"></i> Adresse e-mail</a>
            <a href="{{ route('users.edit.password', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit.password', $user), 'btn-primary', 'btn-tertiary') }}"><i class="fas fa-fw fa-key"></i> Mot de passe</a>
        </div>
        <div class="card p-4">
            <form action="{{ route('users.update.email', $user) }}" method="post">
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

                <div class="text-right">
                    <button class="btn btn-secondary">
                        <i class="fas fa-pencil-alt mr-1"></i> Valider
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
