@extends('layouts.app')

@section('content')

<div class="flex flex-wrap mb-6">
    <a href="{{ route('samples.recent') }}" class="mx-1 btn btn-lg {{ active_class(if_route('samples.recent'), 'btn-primary', 'btn-secondary') }}">Récents</a>
    <a href="{{ route('samples.popular') }}" class="mx-1 btn btn-lg {{ active_class(if_route('samples.popular'), 'btn-primary', 'btn-secondary') }}">Populaires</a>
    <a href="{{ route('samples.random') }}" class="ml-1 btn btn-lg {{ active_class(if_route('samples.random'), 'btn-primary', 'btn-secondary') }}">Hasard</a>
</div>

<samples-index
    :paginator="{{ $samples->toJson() }}"
    :infinite="true"
    ></samples-index>

@endsection
