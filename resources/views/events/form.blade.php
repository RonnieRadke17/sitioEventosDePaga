@extends('layouts.app')
@section('content')

@error('id')
    <div class="text-danger">{{ $message }}</div>
@enderror
     <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="POST" action="{{ $event->exists ? route('events.update', $id) : route('events.store') }}">
                @csrf
                @if($event->exists) 
                    @method('PATCH') 
                @endif
                <div class="form-group">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $event->name) }}" data-original="{{ $event->name }}" required>
                    @error('name') <div>{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <input name="description" class="form-control" value="{{ old('description', $event->description) }}" data-original="{{ $event->description }}" required>
                    @error('description') <div>{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>event_date</label>
                    <input name="event_date" class="form-control" value="{{ old('event_date', $event->event_date) }}" data-original="{{ $event->event_date }}" required>
                    @error('event_date') <div>{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>registration_deadline</label>
                    <input name="registration_deadline" class="form-control" value="{{ old('registration_deadline', $event->registration_deadline) }}" data-original="{{ $event->registration_deadline }}" required>
                    @error('registration_deadline') <div>{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>capacity</label>
                    <input name="capacity" class="form-control" value="{{ old('capacity', $event->capacity) }}" data-original="{{ $event->capacity }}" required>
                    @error('capacity') <div>{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>price</label>
                    <input name="price" class="form-control" value="{{ old('price', $event->price) }}" data-original="{{ $event->price }}" required>
                    @error('price') <div>{{ $message }}</div> @enderror
                </div>



                {{-- <x-forms.input-text name="name" description="Nombre del evento" oldvalue="{{ $event->name ?? ''}}" data-original="{{ $event->name }}" required></x-forms.input-text> --}}

                

                

                <button class="btn btn-primary mt-2">{{ $event->exists ? 'Actualizar' : 'Crear' }}</button>
            </form>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');

        form.addEventListener('submit', function (e) {
            const inputs = form.querySelectorAll('[data-original]');

            inputs.forEach(input => {
                const original = input.getAttribute('data-original');

                // Para inputs tipo texto o textarea
                if ((input.tagName === 'INPUT') && input.value === original) {
                    input.removeAttribute('name');
                }

                // Para selects
                if (input.tagName === 'SELECT' && input.value === original) {
                    input.removeAttribute('name');
                }

                // Si agregas radios/checkboxes, puedes extenderlo aquí
            });
        });
    });
</script>

@endsection