@extends('layouts.app')
@section('content')

    <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="POST" action="{{ $sub->exists ? route('subs.update', $id) : route('subs.store') }}">
                @csrf
                @if($sub->exists) 
                    @method('PUT') 
                @endif
                <div class="form-group">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $sub->name) }}" required>
                    @error('name') <div>{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label>min</label>
                    <input name="min" class="form-control" value="{{ old('min', $sub->min) }}" required>
                    @error('min') <div>{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label>max</label>
                    <input name="max" class="form-control" value="{{ old('max', $sub->max) }}" required>
                    @error('max') <div>{{ $message }}</div> @enderror
                </div>

                <button class="btn btn-primary mt-2">{{ $sub->exists ? 'Actualizar' : 'Crear' }}</button>
            </form>
        </div>
    </div>

{{-- script para mandar solo la información no repetida --}}
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
            });
        });
    });
</script>

@endsection