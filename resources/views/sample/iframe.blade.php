@extends('layouts.base')

@section('title')
    {{ $sample->name }}
@endsection

@section('body-classes', 'theme-legacy')

@section('body')
    <div id="app" class="flex items-center h-screen">
        <div class="flex-1">
            <sample-preview :sample="{{ $sample }}" :iframe="true"></sample-preview>
        </div>
    </div>
@endsection
