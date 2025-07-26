@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Activity Type Details</h1>

    <p>{{$activity->name}}</p>

    @if($types->isEmpty())
        <p>No hay etiquetas activadas.</p>    
    @else
        
    <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="POST" action="{{ $selectedCategories != null ? route('activity-types.update', $id) : route('activity-types.store') }}">
                @csrf
                @if($selectedCategories != null)
                    @method('PATCH')
                @else
                    <input type="hidden" name="activity_id" value="{{ $id }}">
                @endif
                <div class="form-group">
                    @foreach ($types as $type)
                    <input type="checkbox" name="selectedCategories[]" id="selectedCategories" value="{{ $type->id }}" {{ in_array($type->id, $selectedCategories ?? []) ? 'checked' : '' }}>
                        {{ $type->name }}<br>
                    @endforeach
                </div>
                
                <button class="btn btn-primary mt-2">{{ $selectedCategories != null ? 'Actualizar' : 'Crear' }}</button>
            </form>
        </div>
    </div>

    @endif
        
    
    
@endsection