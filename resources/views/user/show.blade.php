@extends('layouts.app')

@section('title')
    Profil de {{ $user->name }}
@endsection

@section('content')

<h1 class="mb-5 uppercase text-sm font-bold text-teal-600">
    Profil de {{ $user->name }}
</h1>

<div class="flex flex-wrap">
        @forelse ($samples as $sample)
            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/6 p-2">
                @include('sample/_square')
            </div>
        @empty
            Aucun sample envoyé par l'utilisateur !
        @endforelse

        {{ $samples->links() }}
</div>
@endsection
