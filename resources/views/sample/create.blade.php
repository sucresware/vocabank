@extends('layouts.app')

@section('title')
    Ajouter un sample
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <form method="POST" action="{{ route('samples.store') }}" enctype="multipart/form-data">
                    <div class="card-body dimmed-border-bottom">
                        <h1 class="h6">Ajouter un sample</h1>
                        @csrf
                        {{--  {!! GoogleReCaptchaV3::renderField('create_sample_id', 'create_sample_action') !!}  --}}

                        {!! BootForm::text('name', 'Nom*') !!}
                        {!! BootForm::text('tags', 'Tags (au moins 3)*') !!}

                        <div class="form-group">
                            <label for="thumbnail" class="col-form-label">Miniature</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="thumbnail" name="thumbnail">
                                <label class="custom-file-label" for="thumbnail" data-browse="Choisir un fichier">Aucun fichier choisi</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body blue">
                        <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="type_vocaroo" name="type" value="type_vocaroo" checked="checked">
                                <label class="custom-control-label" for="type_vocaroo">Ajouter un lien Vocaroo</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="type_youtube" name="type" value="type_youtube" disabled="disabled">
                                <label class="custom-control-label" for="type_youtube">Importer une vidéo YouTube</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="type_upload" name="type" value="type_upload" disabled="disabled">
                                <label class="custom-control-label" for="type_upload">Importer un fichier audio</label>
                            </div>
                        </div>

                        {!! BootForm::text('vocaroo_link', 'Lien de partage Vocaroo*') !!}

                        {{--  {!! BootForm::select('tags', 'Tags (au moins 3)*', [
                        ], old('tags'), ['class' => 'select2-tags', 'multiple' => 'multiple']) !!}  --}}
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
