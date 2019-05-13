@extends('layouts.app_minimal')

@section('title')
    {{ $sample->name }}
@endsection

@section('content')
    @include('sample._preview', $sample)
@endsection
