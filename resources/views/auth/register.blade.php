@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Inscription</div>

                <div class="card-body">
                    {!! BootForm::horizontal([
                        'url' => route('register'),
                        'method' => 'post',
                        'left_column_class' => 'col-md-3',
                        'left_column_offset_class' => 'col-md-offset-3',
                        'right_column_class' => 'col-md-8'
                    ]) !!}
                        @csrf

                        {!! BootForm::text('name', 'Pseudo*', old('name'), ['help_text' => "Attention, contrairement à 4sucres.org, tu peux pas le changer après."]) !!}
                        {!! BootForm::text('email', 'Adresse email*', old('email'), ['help_text' => "Ton email ne sera jamais partagé ou affiché publiquement."]) !!}
                        {!! BootForm::password('password', 'Mot de passe*', ['help_text' => "6 caractères minimum, c'est important pour la sécurité."]) !!}

                       <div class="card-footer bg-light">
                        <div class="text-center mb-3">
                            <small>En t'inscrivant et en utilisant nos services, tu déclares avoir lu et accepter sans réserve les <a href="{{ route('terms') }}">Conditions générales d'utilisation</a>.</small>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Inscription</button>
                        </div>
                    </div>

                    {!! BootForm::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
