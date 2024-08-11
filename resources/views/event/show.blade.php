@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <h1>{{ $event->name }}</h1>
       
    <h2>Imágenes</h2>
    <div class="row">
        @foreach($event->images as $image)
            <div class="col-md-4">
                <img src="{{ asset('storage/' . $image->path) }}" class="img-fluid" alt="{{ $image->alt_text }}">
            </div>
        @endforeach
    </div> --}}
</div>
@endsection
