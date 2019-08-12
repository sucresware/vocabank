@extends('layouts.app')

@section('title')
    Modification du profil {{ $user->name }}
@endsection

@section('content')

<div class="flex flex-wrap justify-center">
    <div class="w-1/4">
        <div class="bg-white shadow p-3">
            <form action="{{ route('users.update', $user) }}" method="post">
                @method('put')
                @csrf

                <div class="mb-3">
                    <label for="name" class="block text-xs mb-1">Nom<span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="border-gray-300 border rounded w-full px-2 py-1" disabled="disabled">
                    @if ($errors->has('name'))
                        <div class="text-red-500 mb-3 text-xs font-bold">
                            <strong>{{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="description" class="block text-xs mb-1">Description</label>
                    <textarea type="text" name="description" id="description" class="border-gray-300 border rounded w-full px-2 py-1 h-32"></textarea>
                    @if ($errors->has('description'))
                        <div class="text-red-500 mb-3 text-xs font-bold">
                            <strong>{{ $errors->first('description') }}
                        </div>
                    @endif
                </div>

                Modifier mon email<br>
                Modifier mon mot de passe<br>
                <br>


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
