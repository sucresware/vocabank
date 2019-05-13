@extends('layouts.app')

@section('title')
    Connexion
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Connexion</div>
                <form method="POST" action="{{ route('login') }}">
                    <div class="card-body">
                        @csrf
                        {!! BootForm::text('email', 'Adresse email*') !!}
                        {!! BootForm::password('password', 'Mot de passe*') !!}
                        {!! BootForm::checkbox('remember', 'Se souvenir de moi', 1, old('remember', 1)) !!}

                    </div>
                    <div class="card-footer bg-light">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Connexion</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
