@extends('layouts.app')
@section('title','Página principal')
@section('content')
@if ($errors->has('error'))
    <div class="alert alert-danger">
        {{ $errors->first('error') }}
    </div>
@endif

<div class="container p-10">
    <h1 class="mb-4">Eventos</h1>
    <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        @foreach($events as $event)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img class="w-full h-48 object-cover" src="{{ asset('storage/'.$event->first_image) }}" alt="Event Image">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $event->name }}</h2>
                    <p class="text-gray-600 mt-2">{{ $event->event_date }}</p>
                    <p class="text-gray-600 mt-2">{{ Str::limit($event->description, 100) }}</p>
                    <div class="mt-4">
                        <a href="{{ route('eventDetails.show',encrypt($event->id)) }}" class="text-indigo-600 hover:text-indigo-800">Ver mas</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

    @auth
        @if(Auth::user()->role->name == 'admin')
            <a href="{{ route('event.index') }}" class="btn btn-primary rounded-circle position-fixed" style="bottom: 20px; left: 20px; width: 50px; height: 50px; display: flex; justify-content: center; align-items: center;"><i class="fas fa-pencil-alt"></i></a>
        @endif
    @endauth


<!-- Incluir Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection
