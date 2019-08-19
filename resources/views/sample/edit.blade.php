@extends('layouts.app')

@section('title')
    Modification de {{ $sample->name }}
@endsection

@section('content')

<sample-edit :sample="{{ $sample }}" :tags="{{ $tags }}"></sample-edit>

@endsection
