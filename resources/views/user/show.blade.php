@extends('layouts.app')

@section('title')
    Profil de {{ $user->name }}
@endsection

@section('content')

<div class="flex flex-wrap">
    <div class="w-full mb-6 xl:w-1/4 xl:mb-0 xl:pr-4">
        <div class="p-3 mb-6 card">
            @if ($user->avatar)
                <img src="/storage/{{ $user->avatar }}" class="h-20 mx-auto mb-6 rounded shadow-lg">
            @endif

            <div class="text-center">
                <span class="font-bold">{{ $user->name }}</span><br>
                <p>{!! nl2br(e($user->description)) !!}</p>
            </div>

            @if (auth()->user() == $user)
                <div class="text-center"><a href="{{ route('users.edit', $user) }}" class="mt-6 btn btn-secondary btn-xs"><i class="mr-1 fas fa-pencil-alt"></i> Modifier</a></div>
        @endif
        </div>
    </div>
    <div class="flex-1 w-full">
        @if (count($samples))
            <samples-index
            :paginator="{{ $samples->toJson() }}"
            :infinite="false"
            ></samples-index>
        @else
            <div class="p-3 mb-3 card">
                Aucun sample envoyé par le membre.
            </div>
        @endif
    </div>
</div>
@endsection
