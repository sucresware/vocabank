@extends('layouts.app')

@section('title')
    Membres
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Profil de {{ $user->name }}
                </div>
                    @forelse ($samples as $sample)
                        <div class="{{ $loop->index%2 ? 'white' : 'blue' }} p-3">
                            @include('sample/_preview')
                        </div>
                    @empty
                        <div class="card-body">
                            Aucun sample envoyé par l'utilisateur !
                        </div>
                    @endforelse

                <div class="card-body">
                    {{ $samples->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
