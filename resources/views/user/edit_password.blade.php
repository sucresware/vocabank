@extends('layouts.app')

@section('title')
    Modification du mot de passe - {{ $user->name }}
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
            <form action="{{ route('users.update.password', $user) }}" method="post">
                @method('put')
                @csrf

                @if ($user->password)
                    <div class="mb-3">
                        <label for="password" class="block text-xs mb-1">Mot de passe actuel<span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" class="border-gray-300 border rounded w-full px-2 py-1">
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
                    <input type="password" name="new_password" id="new_password" class="border-gray-300 border rounded w-full px-2 py-1">
                    @if ($errors->has('new_password'))
                        <div class="text-red-500 mb-3 text-xs font-bold">
                            {{ $errors->first('new_password') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="new_password_confirmation" class="block text-xs mb-1">Nouveau mot de passe (confirmation)<span class="text-red-500">*</span></label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="border-gray-300 border rounded w-full px-2 py-1">
                    @if ($errors->has('new_password_confirmation'))
                        <div class="text-red-500 mb-3 text-xs font-bold">
                            {{ $errors->first('new_password_confirmation') }}
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
