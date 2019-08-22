@extends('layouts.base')

@section('title', '500')

@section('body-classes', 'theme-vocabank w-full h-screen')

@section('body')
<div class="flex w-full h-screen items-center justify-center">
    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w:1/6 text-center mx-4">
        <img src="/svg/logo_white.svg" class="mx-auto mb-6 w-48 animated fadeInDown">
        <div class="animated fadeInUp">
            <div class="mb-6">500</div>
            <a href="{{ route('home') }}" class="btn btn-primary">Retour</a>
        </div>
    </div>
</div>
@endsection