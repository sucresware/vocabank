@extends('layouts.app')

@section('title')
    {{ $sample->name }}
@endsection

@section('content')
    <div class="hidden md:flex mb-4">
        @if ($sample->prev)
            <div>
                <a href="{{ route('samples.prev', $sample) }}" class="btn btn-xs"><i class="fas fa-angle-left"></i></a>
            </div>
        @endif
        @if ($sample->next)
            <div class="ml-auto">
                <a href="{{ route('samples.next', $sample) }}" class="btn btn-xs"><i class="fas fa-angle-right"></i></a>
            </div>
        @endif
    </div>

    <div class="card mb-3">
        <div class="flex flex-wrap">
            <div class="w-full lg:w-auto overflow-hidden flex relative">
                <img src="{{ $sample->thumbnail_url }}" class="object-cover h-64 lg:h-auto w-full" style="filter: blur(10px); transform: scale(1.1);">
                <div class="absolute top-0 left-0 right-0 bottom-0 flex items-center justify-center">
                    <img src="{{ $sample->thumbnail_url }}" class="h-48 w-48 shadow-lg rounded-full">
                </div>
            </div>
            <div class="flex-1">
                @if ($sample->status == \App\Models\Sample::STATUS_PUBLIC)
                    <sample-player :sample="{{ $sample }}"></sample-player>
                    <hr class="mt-0">
                @endif

                <div class="px-4">
                    @if ($sample->status != \App\Models\Sample::STATUS_PUBLIC)
                        <div class="flex flex-wrap mt-4 mb-2">
                            <div class="w-48 text-muted">
                                Statut
                            </div>
                            <div class="w-full lg:w-auto">
                                @include('sample._status')
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-wrap mb-2">
                        <div class="w-48 text-muted">
                            Nom
                        </div>
                        <div class="w-full lg:w-auto font-bold">
                            {{ $sample->name }}
                        </div>
                    </div>

                    @if ($sample->description)
                        <div class="flex flex-wrap mb-2">
                            <div class="w-48 text-muted">
                                Description
                            </div>
                            <div class="w-full lg:w-auto">
                                {!! nl2br(e($sample->description)) !!}
                            </div>
                        </div>
                    @endif

                    @if ($sample->tags->count())
                        <div class="flex flex-wrap mb-2">
                            <div class="w-48 text-muted">
                                Tags
                            </div>
                            <div class="w-full lg:w-auto ">
                                @foreach($sample->tags as $tag)
                                    <a href="{{ route('samples.search') }}?q={{ $tag->name }}&tag=✓" class="btn btn-xs btn-secondary mb-1"><i class="fas fa-hashtag"></i>{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <hr>

                <div class="px-4">
                    <div class="flex flex-wrap mb-2">
                        <div class="w-48 text-muted">
                            Date d'ajout
                        </div>
                        <div class="w-full lg:w-auto ">
                            {{ $sample->presented_date }}
                        </div>
                    </div>
                    <div class="flex flex-wrap mb-2">
                        <div class="w-48 text-muted">
                            Auteur
                        </div>
                        <div class="w-full lg:w-auto ">
                            <a href="{{ route('users.show', $sample->user) }}">{{ $sample->user->name }}</a>
                        </div>
                    </div>
                    <div class="flex flex-wrap mb-2">
                        <div class="w-48 text-muted">
                            Vues
                        </div>
                        <div class="w-full lg:w-auto">
                            <span class="">{{ $sample->views }}</span> ({{ views($sample)->unique()->count() }})
                        </div>
                    </div>
                </div>

                <hr>

                <div class="px-4 mb-4 flex flex-wrap">
                    <button class="w-full lg:w-auto mb-2 lg:mb-0 btn btn-primary mx-1" data-clipboard data-clipboard-text="{{ route('samples.show', $sample) }}" title="Copier le lien"><i class="fa fa-copy"></i> Copier le lien</button>
                    <a href="{{ route('samples.download', $sample) }}" class="w-full lg:w-auto mb-2 lg:mb-0 btn btn-secondary mx-1" title="Télécharger"><i class="fa fa-download"></i> Télécharger</a>
                    @auth
                        @if (($sample->user == auth()->user()) || (auth()->user()->hasRole('admin')))
                            <a href="{{ route('samples.edit', $sample) }}" class="w-full lg:w-auto mb-2 lg:mb-0 btn btn-secondary mx-1 lg:ml-auto"><i class="fas fa-pencil-alt"></i> <span class="ml-1 lg:hidden">Modifier</span></a>
                        @endif
                        @if (auth()->user()->hasRole('admin'))
                            <button
                                class="w-full lg:w-auto mb-2 lg:mb-0 btn btn-secondary mx-1"
                                onclick="event.preventDefault(); document.getElementById('delete-form').submit();"
                            ><i class="fas fa-trash"></i> <span class="ml-1 lg:hidden">Supprimer</span></button>
                            <form id="delete-form" action="{{ route('samples.destroy', $sample) }}" method="POST" style="display: none;">@csrf @method('delete')</form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection
