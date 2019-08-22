@extends('layouts.app')

@section('title', $static_page->name)

@section('content')

<div class="card px-4 py-1 markdown">
    {!! $static_page->parsed_content !!}
</div>

@endsection