@extends('layouts.app')

@section('title')
    {{ $sample->name }}
@endsection

@section('content')
    <div class="flex mb-5 justify-between items-center">
        @if ($sample->prev) <a href="{{ route('samples.prev', $sample) }}" class="nav-link text-3xl"><i class="fas fa-angle-left"></i></a> @endif

        <div class="mx-5 flex-1">
            <div class="card mb-3">
                <div class="flex flex-wrap">
                    <div class="overflow-hidden flex relative">
                        <img src="{{ $sample->thumbnail ? '/storage/' . $sample->thumbnail : '/img/default.png' }}" class="object-cover h-full" style="filter: blur(10px); transform: scale(1.1);">
                        <div class="absolute top-0 left-0 right-0 bottom-0 flex items-center justify-center">
                            <img src="{{ $sample->thumbnail ? '/storage/' . $sample->thumbnail : '/img/default.png' }}" class="h-48 w-48 shadow-lg rounded-full">
                        </div>
                    </div>
                    <div class="flex-1">
                        <sample-player :sample="{{ $sample }}"></sample-player>
                        <div class="p-5 pt-0">

                            <hr class="my-3 mt-0">

                            <div class="flex flex-wrap mb-2">
                                <div class="w-48 text-muted">
                                    Nom
                                </div>
                                <div class="flex-1 font-bold">
                                    {{ $sample->name }}
                                </div>
                            </div>
                            @if ($sample->description)
                            <div class="flex flex-wrap mb-2">
                                <div class="w-48 text-muted">
                                    Description
                                </div>
                                <div class="flex-1">
                                    {!! nl2br(e($sample->description)) !!}
                                </div>
                            </div>
                            @endif
                            @if ($sample->tags->count())
                            <div class="flex flex-wrap mb-2">
                                <div class="w-48 text-muted">
                                    Tags
                                </div>
                                <div class="flex-1 ">
                                    @foreach($sample->tags as $tag)
                                        <a href="{{ route('samples.search') }}?q={{ $tag->name }}" class="btn btn-xs btn-secondary"><i class="fas fa-hashtag"></i>{{ $tag->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <hr class="my-3">

                            <div class="flex flex-wrap mb-2">
                                <div class="w-48 text-muted">
                                    Date d'ajout
                                </div>
                                <div class="flex-1 ">
                                    {{ $sample->presented_date }}
                                </div>
                            </div>
                            <div class="flex flex-wrap mb-2">
                                <div class="w-48 text-muted">
                                    Auteur
                                </div>
                                <div class="flex-1 ">
                                    <a href="{{ route('users.show', $sample->user) }}">{{ $sample->user->name }}</a>
                                </div>
                            </div>
                            <div class="flex flex-wrap mb-2">
                                <div class="w-48 text-muted">
                                    Vues
                                </div>
                                <div class="flex-1">
                                    <span class="">{{ $sample->views }}</span> ({{ views($sample)->unique()->count() }})
                                </div>
                            </div>
                            <div class="mt-10">
                                <a href="#" class="btn btn-primary"><i class="fa fa-copy mr-1"></i> Copier le lien</a>
                                <a href="{{ route('samples.download', $sample) }}" class="btn btn-tertiary"><i class="fa fa-download mr-1"></i> Télécharger</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($sample->next) <a href="{{ route('samples.next', $sample) }}" class="nav-link text-3xl"><i class="fas fa-angle-right"></i></a> @endif
    </div>
</div>
@endsection
