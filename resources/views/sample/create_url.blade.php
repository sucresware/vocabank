@extends('layouts.app')

@section('title')
    Envoyer un fichier
@endsection

@section('content')

<div class="flex flex-wrap mb-6">
    <a href="{{ route('samples.create') }}" class="mx-1 btn btn-lg {{ active_class(if_route('samples.create'), 'btn-primary', 'btn-secondary') }}">Envoyer un fichier</a>
    <a href="{{ route('samples.create.url') }}" class="mx-1 btn btn-lg {{ active_class(if_route('samples.create.url'), 'btn-primary', 'btn-secondary') }}">Importer depuis une URL</a>
</div>

<url-import-wizard></url-import-wizard>

@endsection
