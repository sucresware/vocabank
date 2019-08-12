@extends('layouts.app')

@section('title')
    Profil de {{ $user->name }}
@endsection

@section('content')

<div class="flex flex-wrap">
    <div class="w-1/4">
        <div class="bg-white shadow p-3">
            <div class="float-right">
                <a href="{{ route('users.edit', $user) }}" class="text-gray-500 hover:text-gray-600"><i class="fas fa-edit"></i></a>
            </div>
            <img src="/storage/{{ $user->avatar }}" class="h-20 shadow-lg rounded -mt-8 mb-3">
            <div class="text-teal-500 font-bold mb-3">{{ $user->name }}</div>
            <p class="mb-3">{!! nl2br(e($user->description)) !!}</p>

            <div class="flex">
            <div class="mb-3 mx-3 font-bold">
                <div class="text-3xl pb-0">2</div>
                <div class="-mt-2">secondes</div>
            </div>
            <div class="mb-3 mx-3 font-bold">
                <div class="text-3xl pb-0">1</div>
                <div class="-mt-2">samples</div>
            </div>
            <div class="mb-3 mx-3 font-bold">
                <div class="text-3xl pb-0">15</div>
                <div class="-mt-2">écoutes</div>
            </div>
            </div>
        </div>
        <div class="bg-white shadow p-3">
        <div class="block text-xs mb-2">
                <img src="/img/4sucres.png" class="inline h-5 mr-1" alt=""> Compte 4sucres
            </div>
            <div class="flex items-center border-gray-300 border rounded w-full px-3 py-2 relative mb-3">
                <div class="mr-3">
                    <img src="{{ $user->fourSucres_account['avatar'] }}" class="h-4">
                </div>
                <div class="flex-1 text-xs">
                    {{ $user->fourSucres_account['name'] }}<br>
                    <a href="{{ $user->fourSucres_account['user']['link'] }}" target="_blank" class="text-gray-600 hover:text-gray-500">{{ $user->fourSucres_account['user']['link'] }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="pl-5 flex-1">
        @if (count($samples))
            <samples-index
            :paginator="{{ $samples->toJson() }}"
            :infinite="false"
            ></samples-index>
        @else
            <div class="bg-white border rounded shadow mb-3 p-3 text-gray-500">
                Aucun sample envoyé par le membre.
            </div>
        @endif
    </div>
</div>
@endsection
