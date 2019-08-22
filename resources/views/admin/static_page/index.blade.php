@extends('layouts.app')

@section('title')
    Pages statiques
@endsection

@section('content')
    @include('admin._nav')

    <div class="mb-6">
        <a href="{{ route('admin.static-pages.create') }}" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Créer une page</a>
    </div>

    @foreach ($static_pages as $static_page)
        <div class="card mb-2 p-3 flex items-center">
            <div>
                <span class="font-bold">{{ $static_page->name }}</span> ({{ $static_page->slug }})
            </div>

            <div class="ml-auto">
                <a href="{{ route('pages', $static_page->slug) }}" class="btn btn-xs btn-secondary" target="_blank"><i class="fa fa-fw fa-eye"></i></a>
                <a href="{{ route('admin.static-pages.edit', $static_page) }}" class="btn btn-xs btn-secondary"><i class="fa fa-fw fa-pencil-alt"></i></a>
                <button class="btn btn-xs btn-secondary" onclick="event.preventDefault(); if (confirm('Voulez-vous vraiment supprimer cette page ?')) document.getElementById('delete-form-{{ $static_page->id }}').submit();"><i class="fa fa-fw fa-trash"></i></button>
                <form id="delete-form-{{ $static_page->id }}" action="{{ route('admin.static-pages.destroy', $static_page) }}" method="POST" style="display: none;">@csrf @method('delete')</form>
            </div>
        </div>
    @endforeach
@endsection
