@extends('layouts.app')

@section('title')
    Modification du profil - {{ $user->name }}
@endsection

@section('content')

<div class="flex justify-center mb-6 md:justify-start">
    <a href="{{ route('users.edit', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-user"></i> Profil</a>
    <a href="{{ route('users.edit.email', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit.email', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-at"></i> Adresse e-mail</a>
    <a href="{{ route('users.edit.password', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit.password', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-key"></i> Mot de passe</a>
</div>

<form action="{{ route('users.update', $user) }}" method="post" enctype="multipart/form-data">
    <div class="p-4 mb-4 card">
        @method('put')
        @csrf

        <div class="mb-3">
            <label for="name" class="block mb-1 text-xs">Nom<span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full form-control" disabled="disabled">
            @if ($errors->has('name'))
                <div class="mb-3 text-xs font-bold text-red-500">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="name" class="block mb-1 text-xs">Photo de profil</label>
            <input type="file" name="avatar" id="avatar" value="{{ old('avatar', $user->avatar) }}" class="w-full form-control">
            @if ($errors->has('avatar'))
                <div class="mb-3 text-xs font-bold text-red-500">
                    {{ $errors->first('avatar') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="description" class="block mb-1 text-xs">Description</label>
            <textarea type="text" name="description" id="description" class="w-full h-32 form-control">{{ old('description', $user->description) }}</textarea>
            @if ($errors->has('description'))
                <div class="mb-3 text-xs font-bold text-red-500">
                    {{ $errors->first('description') }}
                </div>
            @endif
        </div>
    </div>
    <div>
        <div class="flex justify-between">
            <a href="{{ route('users.delete', $user) }}" class="btn btn-secondary">
                <i class="text-red-500 fas fa-fw fa-trash"></i> Supprimer mon compte
            </a>
            <button class="btn btn-primary">
                Valider
            </button>
        </div>
    </div>
</form>

@endsection
