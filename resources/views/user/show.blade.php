@extends('layouts.app')

@section('title')
    Profil de {{ $user->name }}
@endsection

@section('content')

<div class="flex flex-wrap">
    <div class="w-1/4">
        <div class="bg-white shadow p-3">
            <div class="float-right">
                <i class="fas fa-edit"></i>
            </div>
            <div class="text-teal-500 font-bold mb-3">{{ $user->name }}</div>
            <p class="mb-3">{{ $user->description }}</p>
            <div class="mb-3 text-gray-600"><i class="fas fa-link"></i> 4sucres.org/u/YvonEnbaver</div>
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
