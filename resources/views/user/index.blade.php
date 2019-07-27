@extends('layouts.app')

@section('title')
    Membres
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @foreach ($users as $user)
                    <div class="{{ $loop->index%2 ? 'white' : 'blue' }} p-3">
                        <strong><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a></strong><br>
                        {{ $days = $user->created_at->diffInDays(now()) }} {{ str_plural('jour', $days) }} <span class="text-muted">/</span> {{ $samples = $user->samples()->public()->count() }} {{ str_plural('sample', $samples) }}
                    </div>
                @endforeach
                <div class="card-body">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
