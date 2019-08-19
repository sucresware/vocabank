@extends('layouts.app')

@section('title')
    Modification du profil - {{ $user->name }}
@endsection

@section('content')

<div class="flex mb-6">
    <a href="{{ route('users.edit', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-user"></i> Profil</a>
    <a href="{{ route('users.edit.email', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit.email', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-at"></i> Adresse e-mail</a>
    <a href="{{ route('users.edit.password', $user) }}" class="mx-1 btn btn-lg {{ active_class(if_route('users.edit.password', $user), 'btn-primary', 'btn-secondary') }}"><i class="fas fa-fw fa-key"></i> Mot de passe</a>
</div>

<form action="{{ route('users.update', $user) }}" method="post" enctype="multipart/form-data">
    <div class="card p-4 mb-4">
        @method('put')
        @csrf

        <div class="mb-3">
            <label for="name" class="block text-xs mb-1">Nom<span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control w-full" disabled="disabled">
            @if ($errors->has('name'))
                <div class="text-red-500 mb-3 text-xs font-bold">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="name" class="block text-xs mb-1">Photo de profil</label>
            <input type="file" name="avatar" id="avatar" value="{{ old('avatar', $user->avatar) }}" class="form-control w-full">
            @if ($errors->has('avatar'))
                <div class="text-red-500 mb-3 text-xs font-bold">
                    {{ $errors->first('avatar') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="description" class="block text-xs mb-1">Description</label>
            <textarea type="text" name="description" id="description" class="form-control w-full h-32">{{ old('description', $user->description) }}</textarea>
            @if ($errors->has('description'))
                <div class="text-red-500 mb-3 text-xs font-bold">
                    {{ $errors->first('description') }}
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
