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
                <div class="card">
                    <div class="card-header">
                        Les plus populaires
                    </div>

                    @foreach ($popular_samples as $sample)
                        <div class="{{ $loop->index%2 ? 'white' : 'blue' }} p-3">
                            @include('sample/_preview')
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        @if (isset($recent_samples) && count($recent_samples))
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Les plus récents
                    </div>

                    @foreach ($recent_samples as $sample)
                        <div class="{{ $loop->index%2 ? 'white' : 'blue' }} p-3">
                            @include('sample/_preview')
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        @isset($samples)
            <div class="col-md-6">
                <div class="card">
                    @foreach ($samples as $sample)
                        <div class="{{ $loop->index%2 ? 'white' : 'blue' }} p-3">
                            @include('sample/_preview')
                        </div>
                    @endforeach
                    <div class="card-body">
                        {{ $samples->links() }}
                    </div>
                </div>
            </div>
        @endisset
    </div>
</div>
@endsection
