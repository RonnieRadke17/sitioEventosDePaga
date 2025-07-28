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
        <form method="POST" action="{{ $selectedTypes != null ? route('activity-types.update', $id) : route('activity-types.store') }}">
            @csrf
            @if($selectedTypes != null)
                @method('PATCH')
            @else
                <input type="hidden" name="activity_id" value="{{ $id }}">
            @endif
            
            <input type="hidden" id="original-selectedTypes" value="{{ implode(',', $selectedTypes ?? []) }}">

            <div class="form-group">
                @foreach ($types as $type)
                    <input
                        type="checkbox"
                        name="selectedTypes[]"
                        value="{{ $type->id }}"
                        {{ in_array($type->id, $selectedTypes ?? []) ? 'checked' : '' }}>
                    {{ $type->name }}<br>
                @endforeach

                @error('selectedTypes')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                @error('selectedTypes.*')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary mt-2">{{ $selectedTypes != null ? 'Actualizar' : 'Crear' }}</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');

        form.addEventListener('submit', function () {
            // Obtenemos los valores originales desde el input oculto
            const originalTypes = document.getElementById('original-selectedTypes')?.value.split(',') ?? [];
            const checkboxes = form.querySelectorAll('input[name="selectedTypes[]"]');

            const selectedNow = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            const sameValues = selectedNow.length === originalTypes.length &&
                selectedNow.every(val => originalTypes.includes(val));

            if (sameValues) {
                checkboxes.forEach(cb => cb.removeAttribute('name'));
            }
        });
    });
</script>


    @endif

    
@endsection