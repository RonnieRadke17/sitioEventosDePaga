@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Categorias del evento</h1>

    <p>Nombre del evento:{{$event->name}}</p>

    @if($categories->isEmpty())
        <p>No hay etiquetas activadas.</p>    
    @else
        

    <div class="row justify-content-center">
    <div class="col-md-4">
        <form method="POST" action="{{ $selectedCategories != null ? route('category-events.update', $id) : route('category-events.store') }}">
            @csrf
            @if($selectedCategories != null)
                @method('PATCH')
            @else
                <input type="hidden" name="event_id" value="{{ $id }}">
            @endif

            {{-- Campo oculto para valores originales --}}
            <input type="hidden" id="original-selectedCategories" value="{{ implode(',', $selectedCategories ?? []) }}">

            <div class="form-group">
                @foreach ($categories as $category)
                    <input
                        type="checkbox"
                        name="selectedCategories[]"
                        value="{{ $category->id }}"
                        {{ in_array($category->id, $selectedCategories ?? []) ? 'checked' : '' }}>
                    {{ $category->name }}<br>
                @endforeach

                @error('selectedCategories')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

                @error('selectedCategories.*')
                    <div class="text-danger">{{ $message }}</div>
                @enderror

               
            </div>

            <button class="btn btn-primary mt-2">{{ $selectedCategories != null ? 'Actualizar' : 'Crear' }}</button>
        </form>
    </div>
</div>

{{-- Script para evitar enviar si no hubo cambios --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');

        form.addEventListener('submit', function () {
            const originalCategories = document.getElementById('original-selectedCategories')?.value.split(',') ?? [];
            const checkboxes = form.querySelectorAll('input[name="selectedCategories[]"]');

            const selectedNow = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            const sameValues = selectedNow.length === originalCategories.length &&
                selectedNow.every(val => originalCategories.includes(val));

            if (sameValues) {
                checkboxes.forEach(cb => cb.removeAttribute('name'));
            }
        });
    });
</script>


    @endif
     
@endsection