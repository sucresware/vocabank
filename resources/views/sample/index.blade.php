@extends('layouts.app')

@section('content')

<div class="flex flex-wrap mb-6 items-center">
    <a href="{{ route('samples.recent') }}" class="mr-1 px-4 py-2 font-bold rounded-full text-white bg-teal-400">Récents</a>
    <a href="{{ route('samples.popular') }}" class="mx-1 px-4 py-2 font-bold rounded-full hover:bg-gray-200">Populaires</a>
    <a href="{{ route('samples.random') }}" class="ml-1 px-4 py-2 font-bold rounded-full hover:bg-gray-200">Hasard</a>
</div>

<div class="bg-white border rounded shadow mb-3">
    @isset($samples)
        <div class="col-md-6">
            @foreach ($samples as $sample)
                @include('sample/_list')
            @endforeach
            <div class="card-body">
                {{ $samples->links() }}
            </div>
        </div>
    @endisset
</div>
@endsection
