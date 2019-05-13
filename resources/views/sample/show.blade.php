@extends('layouts.app')

@section('title')
    {{ $sample->name }}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Sample <strong>#{{ $sample->id }}</strong>
                </div>
                <div class="card-body blue text-center dimmed-border-bottom">
                    <img src="{{ $sample->thumbnail_link }}" class="img-fluid rounded mb-4 shadow" style="max-height: 150px; width: 150px;">
                    {!! $sample->render() !!}
                </div>
                <div class="card-body white row no-gutters dimmed-border-bottom">
                    <div class="col-3 pr-2">
                        <strong>Nom</strong>
                    </div>
                    <div class="col-9">
                        {{ $sample->name }}
                    </div>
                </div>
                <div class="card-body blue row no-gutters dimmed-border-bottom">
                    <div class="col-3 pr-2">
                        <strong>Tags</strong>
                    </div>
                    <div class="col-9">
                        @foreach($sample->tags as $tag)
                            <a href="{{ route('samples.search') }}?q={{ $tag->name }}">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </div>
                <div class="card-body white row no-gutters dimmed-border-bottom">
                    <div class="col-3 pr-2">
                        <strong>Ajouté</strong>
                    </div>
                    <div class="col-9">
                        le {{ $sample->created_at->format('d/m/Y à H:i') }} par <a href="{{ route('users.show', $sample->user) }}">{{ $sample->user->name }}</a>
                    </div>
                </div>
                <div class="card-body blue row no-gutters dimmed-border-bottom">
                    {{--  <div class="col-3 pr-2">
                        <strong>Lien direct</strong>
                    </div>
                    <div class="col-9">
                        <a href="{{ $sample->vocaroo_link }}">{{ $sample->vocaroo_link }}</a>
                    </div>  --}}
                    <div class="col-3 pr-2">
                        <strong>Vues</strong>
                    </div>
                    <div class="col-9">
                        {{ views($sample)->count() }}
                    </div>
                </div>
                <div class="card-body white row no-gutters dimmed-border-bottom">
                    <div class="col-3 pr-2">
                        <strong>Vues uniques</strong>
                    </div>
                    <div class="col-9">
                        {{ views($sample)->unique()->count() }}
                    </div>
                </div>
                <div class="card-body blue row no-gutters dimmed-border-bottom align-items-center">
                    <div class="col-3 pr-2">
                        <strong>Lien Vocaroo</strong>
                    </div>
                    <div class="col-9">
                        <div class="input-group">
                            <input id="v{{ $sample->id }}" value="{{ $sample->vocaroo_link }}" class="form-control">
                            <div class="input-group-append">
                                <button class="btn btn-primary" data-clipboard-target="#v{{ $sample->id }}" data-clipboard><i class="fas fa-link fa-fw mr-1"></i> Copier le lien</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body white row no-gutters dimmed-border-bottom align-items-center">
                    <div class="col-3 pr-2">
                        <strong>Partage VocaBank</strong>
                    </div>
                    <div class="col-9">
                        <div class="input-group">
                            <input id="s{{ $sample->id }}" value="{{ route('samples.show', $sample) }}" class="form-control">
                            <div class="input-group-append">
                                <button class="btn btn-primary" data-clipboard-target="#s{{ $sample->id }}" data-clipboard><i class="fas fa-link fa-fw mr-1"></i> Copier le lien</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body blue text-center">

                    <a href="{{ route('samples.download', $sample) }}" class="btn btn-primary">Télécharger</a>
                    <a href="mailto:contact@4sucres.org" class="btn btn-outline-danger">Signaler</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
