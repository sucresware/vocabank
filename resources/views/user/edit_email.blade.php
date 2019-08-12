@extends('layouts.app')

@section('title')
    Modification de l'adresse e-mail - {{ $user->name }}
@endsection

@section('content')

<div class="flex flex-wrap justify-center mx-2">
    <div class="w-1/6 mx-2">
        <a href="{{ route('users.edit', $user) }}" class="mx-1 px-4 py-2 font-bold rounded-full {{ active_class(if_route('users.edit', $user), 'text-white bg-teal-500', 'hover:bg-gray-200') }} block"><i class="fas fa-fw fa-user"></i> Profil</a>
        <a href="{{ route('users.edit.email', $user) }}" class="mx-1 px-4 py-2 font-bold rounded-full {{ active_class(if_route('users.edit.email', $user), 'text-white bg-teal-500', 'hover:bg-gray-200') }} block"><i class="fas fa-fw fa-at"></i> Adresse e-mail</a>
        <a href="{{ route('users.edit.password', $user) }}" class="mx-1 px-4 py-2 font-bold rounded-full {{ active_class(if_route('users.edit.password', $user), 'text-white bg-teal-500', 'hover:bg-gray-200') }} block"><i class="fas fa-fw fa-key"></i> Mot de passe</a>
    </div>
    <div class="w-1/3 mx-2">
        <div class="bg-white shadow p-3 mb-3">
            <form action="{{ route('users.update.email', $user) }}" method="post">
                @method('put')
                @csrf

                <div class="mb-3">
                    <label for="name" class="block text-xs mb-1">Email<span class="text-red-500">*</span></label>
                    <input type="text" name="email" id="email" value="{{ old('email', $user->email) }}" class="border-gray-300 border rounded w-full px-2 py-1">
                    @if ($errors->has('email'))
                        <div class="text-red-500 mb-3 text-xs font-bold">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <div class="text-right">
                    <button class="inline-block px-3 py-1 font-bold rounded-full bg-gray-300 hover:bg-gray-400">
                        <i class="fa fa-edit mr-1"></i> Valider
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
