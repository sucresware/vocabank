@extends('layouts.app')

@section('title')
    Modification de {{ $static_page->name }}
@endsection

@section('content')

@include('admin._nav')

<form action="{{ route('admin.static-pages.update', $static_page) }}" method="post">
    <div class="card p-4 mb-4">
        @method('put')
        @csrf

        <div class="mb-3">
            <label for="name" class="block text-xs mb-1">Nom<span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $static_page->name) }}" class="form-control w-full">
            @if ($errors->has('name'))
                <div class="text-red-500 mb-3 text-xs font-bold">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="slug" class="block text-xs mb-1">Slug<span class="text-red-500">*</span></label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $static_page->slug) }}" class="form-control w-full">
            @if ($errors->has('slug'))
                <div class="text-red-500 mb-3 text-xs font-bold">
                    {{ $errors->first('slug') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="content" class="block text-xs mb-1">Contenu (markdown)<span class="text-red-500">*</span></label>
            <textarea name="content" id="content" class="form-control w-full h-64">{{ old('content', $static_page->content) }}</textarea>
            @if ($errors->has('content'))
                <div class="text-red-500 mb-3 text-xs font-bold">
                    {{ $errors->first('content') }}
                </div>
            @endif
        </div>
    </div>
    <div class="text-right">
        <button class="btn btn-primary">
            Valider
        </button>
    </div>
</form>

@endsection
