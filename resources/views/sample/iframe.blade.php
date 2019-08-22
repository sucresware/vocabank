@extends('layouts.base')

@section('title', $sample->name)

@section('body-classes', 'theme-legacy')

@section('body')
    <div id="app" class="flex items-center h-screen">
        <sample-preview :sample="{{ $sample }}" :iframe="true"></sample-preview>
    </div>
@endsection
