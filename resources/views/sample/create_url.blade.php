@extends('layouts.app')

@section('title')
    Importer depuis une URL
@endsection

@section('content')

<div class="flex flex-wrap mb-6">
    <a href="{{ route('samples.create') }}" class="mx-1 btn btn-lg {{ active_class(if_route('samples.create'), 'btn-primary', 'btn-secondary') }}">Envoyer un fichier</a>
    <a href="{{ route('samples.create.url') }}" class="mx-1 btn btn-lg {{ active_class(if_route('samples.create.url'), 'btn-primary', 'btn-secondary') }}">Importer depuis une URL</a>
</div>

<import-url-wizard></import-url-wizard>

@endsection
