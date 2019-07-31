@extends('layouts.app')

@section('content')

<div class="flex flex-wrap mb-6 items-center">
    <a href="{{ route('samples.recent') }}" class="mr-1 px-4 py-2 font-bold rounded-full {{ active_class(if_route('samples.recent'), 'text-white bg-teal-500', 'hover:bg-gray-200') }}">Récents</a>
    <a href="{{ route('samples.popular') }}" class="mx-1 px-4 py-2 font-bold rounded-full {{ active_class(if_route('samples.popular'), 'text-white bg-teal-500', 'hover:bg-gray-200') }}">Populaires</a>
    <a href="{{ route('samples.random') }}" class="ml-1 px-4 py-2 font-bold rounded-full {{ active_class(if_route('samples.random'), 'text-white bg-teal-500', 'hover:bg-gray-200') }}">Hasard</a>
</div>

<samples-index
    :paginator="{{ $samples->toJson() }}"
    :infinite="true"
    ></samples-index>

@endsection
