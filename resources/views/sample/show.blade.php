@extends('layouts.app')

@section('title')
    {{ $sample->name }}
@endsection

@section('content')

    <div class="flex mb-5 justify-between items-center">
        @if ($sample->prev)
            <div>
                <a href="{{ route('samples.prev', $sample) }}" class="text-3xl text-gray-400 hover:text-gray-600"><i class="fas fa-angle-left"></i></a>
            </div>
        @endif
        <div class="mx-5 flex-1">
            <div class="bg-white border rounded shadow mb-3">
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
                            <div class="my-3 mt-0 bg-gray-200" style="height: 1px;"></div>
                            <div class="flex flex-wrap mb-2">
                                <div class="w-48 text-gray-500">
                                    Nom
                                </div>
                                <div class="flex-1 font-bold">
                                    {{ $sample->name }}
                                </div>
                            </div>
                            @if ($sample->description)
                            <div class="flex flex-wrap mb-2">
                                <div class="w-48 text-gray-500">
                                    Description
                                </div>
                                <div class="flex-1 ">
                                    {{ nl2br($sample->description) }}
                                </div>
                            </div>
                            @endif
                            <div class="flex flex-wrap mb-2">
                                <div class="w-48 text-gray-500">
                                    Tags
                                </div>
                                <div class="flex-1 ">
                                    @foreach($sample->tags as $tag)
                                        <a href="{{ route('samples.search') }}?q={{ $tag->name }}" class="text-xs py-1 mb-1 px-2 bg-gray-200 rounded-full hover:bg-gray-300 mr-1 inline-block">{{ $tag->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                            <div class="my-3 bg-gray-200" style="height: 1px;"></div>
                            <div class="flex flex-wrap mb-2">
                                <div class="w-48 text-gray-500">
                                    Date d'ajout
                                </div>
                                <div class="flex-1 ">
                                    {{ $sample->created_at->format('d/m/Y à H:i') }}
                                </div>
                            </div>
                            <div class="flex flex-wrap mb-2">
                                <div class="w-48 text-gray-500">
                                    Auteur
                                </div>
                                <div class="flex-1 ">
                                    <a class="text-gray-600 hover:text-gray-500" href="{{ route('users.show', $sample->user) }}">{{ $sample->user->name }}</a>
                                </div>
                            </div>
                            <div class="flex flex-wrap mb-2">
                                <div class="w-48 text-gray-500">
                                    Vues
                                </div>
                                <div class="flex-1">
                                    <span class="">{{ views($sample)->count() }}</span> ({{ views($sample)->unique()->count() }})
                                </div>
                            </div>
                            <div class="mt-10">
                                <a href="#" class="inline-block mr-1 px-3 py-1 font-bold rounded-full bg-gray-300 hover:bg-gray-400"><i class="fa fa-copy mr-1"></i> Copier le lien</a>
                                <a href="#" class="inline-block mr-1 px-3 py-1 font-bold rounded-full bg-gray-300 hover:bg-gray-400"><i class="fa fa-heart"></i></a>
                                <a href="{{ route('samples.download', $sample) }}" class="inline-block mr-1 px-3 py-1 font-bold rounded-full hover:bg-gray-200"><i class="fa fa-download mr-1"></i> Télécharger</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($sample->next)
        <div>
            <a href="{{ route('samples.next', $sample) }}" class="text-3xl text-gray-400 hover:text-gray-600"><i class="fas fa-angle-right"></i></a>
        </div>
        @endif
    </div>


</div>
@endsection
