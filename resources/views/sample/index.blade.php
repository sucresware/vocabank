@extends('layouts.app')

@section('content')

@if (!isset($q))
    <div class="flex justify-center md:justify-start mb-6">
        <a href="{{ route('samples.index') }}?order=recent" class="mx-1 btn btn-lg {{ active_class(if_route('samples.index' && (if_query('order', 'recent') || if_query('order', null))) , 'btn-primary', 'btn-secondary') }}">Récents</a>
        <a href="{{ route('samples.index') }}?order=popular" class="mx-1 btn btn-lg {{ active_class(if_route('samples.index') && if_query('order', 'popular'), 'btn-primary', 'btn-secondary') }}">Populaires</a>
        <a href="{{ route('samples.random') }}" class="ml-1 btn btn-lg {{ active_class(if_route('samples.random'), 'btn-primary', 'btn-secondary') }}">Hasard</a>
    </div>
@endif

<samples-index
    :paginator="{{ $samples->toJson() }}"
    :infinite="false"
    ></samples-index>
@endsection
