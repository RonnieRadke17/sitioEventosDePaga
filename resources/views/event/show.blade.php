@extends('layouts.app')

@section('content')
<div class="container">
    <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img class="w-full h-48 object-cover" src="{{ asset('storage/'.$event->first_image) }}" alt="Event Image">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $event->name }}</h2>
                    <p class="text-gray-600 mt-2">{{ $event->event_date }}</p>
                    <p class="text-gray-600 mt-2">{{ Str::limit($event->description, 100) }}</p>
                </div>
            </div>
        
    </div>
</div>
@endsection
