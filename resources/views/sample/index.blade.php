@extends('layouts.app')

@section('main')
    <div class="blue shadow">
        <div class="container py-3">
            <div class="row align-items-center justify-content-center">
                <div class="col-8">
                    <form action="{{ route('samples.search') }}" method="get">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-sm" name="q" value="{{ old('q', $q ?? '') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-sm" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="w-100"></div>
                <div class="col-auto"><a href="{{ route('samples.search') }}">Recherche</a></div>
                <div class="col-auto"><a href="{{ route('samples.popular') }}">Populaires</a></div>
                <div class="col-auto"><a href="{{ route('samples.recent') }}">Récents</a></div>
                <div class="col-auto"><a href="{{ route('samples.random') }}">Hasard</a></div>
            </div>
        </div>
    </div>

    <div class="container">

    </div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if (isset($popular_samples) && count($popular_samples))
            <div class="col-md-6">
                <h1>Les plus populaires</h1>

                @foreach ($popular_samples as $sample)
                    @include('sample/_preview')
                @endforeach
            </div>
        @endif
        @if (isset($recent_samples) && count($recent_samples))
            <div class="col-md-6">
                <h1>Les plus récents</h1>
                @foreach ($recent_samples as $sample)
                    @include('sample/_preview')
                @endforeach
            </div>
        @endif
        @isset($samples)
            <div class="col-md-6">
                @foreach ($samples as $sample)
                    @include('sample/_preview')
                @endforeach
                <div class="card-body">
                    {{ $samples->links() }}
                </div>
            </div>
        @endisset
    </div>
</div>
@endsection
