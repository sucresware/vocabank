@extends('layouts.app')

@section('title')
    {{ $sample->name }}
@endsection

@section('content')
    <div class="hidden mb-4 md:flex">
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

    <div class="mb-3 card">
        <div class="flex flex-wrap">
            <div class="relative flex w-full overflow-hidden lg:w-auto">
                <img src="{{ $sample->thumbnail_url }}" class="object-cover w-full h-64 lg:h-auto" style="filter: blur(10px); transform: scale(1.1);">
                <div class="absolute top-0 bottom-0 left-0 right-0 flex items-center justify-center">
                    <img src="{{ $sample->thumbnail_url }}" class="w-48 h-48 rounded-full shadow-lg">
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
                        <div class="w-full font-bold lg:w-auto">
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
                                    <a href="{{ route('samples.search') }}?q={{ $tag->name }}&tag=✓" class="mb-1 btn btn-xs btn-secondary"><i class="fas fa-hashtag"></i>{{ $tag->name }}</a>
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
                </div>

                <hr>

                <div class="flex flex-wrap px-4 mb-4">
                    <button class="w-full mx-1 mb-2 lg:w-auto lg:mb-0 btn btn-primary" data-clipboard data-clipboard-text="{{ route('samples.show', $sample) }}" title="Copier le lien"><i class="fa fa-copy"></i> <span class="lg:hidden xl:inline">Copier le lien</span></button>
                    @auth
                        <button class="w-full lg:w-auto mb-2 lg:mb-0 btn @if ($sample->liked) btn-primary @else btn-secondary @endif mx-1" title="Favori" onclick="event.preventDefault(); document.getElementById('like-form').submit();"><i class="fas fa-heart"></i> <span class="lg:hidden xl:inline">Favori</span></button>
                        <form action="{{ route('samples.like', $sample) }}" id="like-form" method="post" class="hidden">@csrf</form>
                    @endauth
                    <a href="{{ route('samples.download', $sample) }}" class="w-full mx-1 mb-2 lg:w-auto lg:mb-0 btn btn-secondary" title="Télécharger"><i class="fa fa-download"></i> <span class="lg:hidden xl:inline">Télécharger</span></a>
                    @auth
                        @if (($sample->user == auth()->user()) || (auth()->user()->hasRole('admin')))
                            <a href="{{ route('samples.edit', $sample) }}" class="w-full mx-1 mb-2 lg:w-auto lg:mb-0 btn btn-secondary lg:ml-auto"><i class="fas fa-pencil-alt"></i> <span class="ml-1 lg:hidden">Modifier</span></a>
                        @endif
                        @if (auth()->user()->hasRole('admin'))
                            <button
                                class="w-full mx-1 mb-2 lg:w-auto lg:mb-0 btn btn-secondary"
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
