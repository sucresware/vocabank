@extends('layouts.app')

@section('title')
    {{ $sample->name }}
@endsection

@section('content')
    <div class="flex justify-between mb-4">
        @if ($sample->prev)
            <a href="{{ route('samples.prev', $sample) }}" class="mr-auto cursor-pointer px-3 py-1 font-bold rounded-full hover:bg-gray-300 text-xs"><i class="fas fa-angle-left"></i></a>
        @endif
        @if ($sample->next)
            <a href="{{ route('samples.next', $sample) }}" class="ml-auto cursor-pointer px-3 py-1 font-bold rounded-full hover:bg-gray-300 text-xs"><i class="fas fa-angle-right"></i></a>
        @endif
    </div>

    <div class="mx-4 flex-1">
        <div class="card mb-3">
            <div class="flex flex-wrap">
                <div class="overflow-hidden flex relative">
                    <img src="{{ $sample->thumbnail_url }}" class="object-cover h-full" style="filter: blur(10px); transform: scale(1.1);">
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
                                <div class="flex-1">
                                    @include('sample._status')
                                </div>
                            </div>
                        @endif

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
                    </div>

                    <hr>

                    <div class="px-4 mb-4 flex ">
                        <div class="mr-auto">
                            <a href="#" class="btn btn-primary mx-1 "><i class="fa fa-copy mr-1"></i> Copier le lien</a>
                            <a href="{{ route('samples.download', $sample) }}" class="btn btn-tertiary mx-1"><i class="fa fa-download mr-1"></i> Télécharger</a>
                        </div>
                        <div class="ml-auto">
                            @if (($sample->user == auth()->user()) || (auth()->user()->hasRole('admin')))
                                <a href="{{ route('samples.edit', $sample) }}" class="btn btn-secondary"><i class="fas fa-pencil-alt"></i></a>
                            @endif
                            @if (auth()->user()->hasRole('admin'))
                                <button
                                    class="btn btn-secondary"
                                    onclick="event.preventDefault(); document.getElementById('delete-form').submit();"
                                ><i class="fas fa-trash"></i></button>
                                <form id="delete-form" action="{{ route('samples.destroy', $sample) }}" method="POST" style="display: none;">@csrf @method('delete')</form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
