@extends('layouts.app')

@section('title')
    Profil de {{ $user->name }}
@endsection

@section('content')

<div class="flex flex-wrap">
    <div class="w-full xl:w-1/4 mb-6 xl:mb-0 xl:pr-4">
        <div class="card mb-6 p-3">
            @if ($user->avatar)
                <img src="/storage/{{ $user->avatar }}" class="mx-auto h-20 shadow-lg rounded mb-6">
            @endif

            <div class="text-center">
                <span class="font-bold">{{ $user->name }}</span><br>
                <p>{!! nl2br(e($user->description)) !!}</p>
            </div>

            @if (auth()->user() == $user)
                <div class="text-center"><a href="{{ route('users.edit', $user) }}" class="mt-6 btn btn-secondary btn-xs"><i class="fas fa-pencil-alt mr-1"></i> Modifier mon profil</a></div>
        @endif
        </div>

        @if ($user->fourSucres_account)
            <div class="card mb-6 p-3">
                <div class="flex items-center">
                    <div class="mr-2">
                        <img src="/img/4sucres.png" class="inline h-5" alt="">
                    </div>
                    <div class="flex-1 text-xs">
                        <a href="{{ $user->fourSucres_account['user']['link'] }}" target="_blank">{{ '@' . $user->fourSucres_account['name'] }}</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="flex-1">
        @if (count($samples))
            <samples-index
            :paginator="{{ $samples->toJson() }}"
            :infinite="false"
            ></samples-index>
        @else
            <div class="card mb-3 p-3">
                Aucun sample envoyé par le membre.
            </div>
        @endif
    </div>
</div>
@endsection
