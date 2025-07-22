@extends('layouts.app')
@section('content')

@error('id')
    <div class="text-danger">{{ $message }}</div>
@enderror




     <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="POST" action="{{ $activity->exists ? route('activities.update', $id) : route('activities.store') }}">
                @csrf
                @if($activity->exists) 
                    @method('PATCH') 
                    {{-- <input type="hidden" name="id" value="{{ $activity->exists ? $id : '' }}"> --}}
                @endif
                <div class="form-group">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $activity->name) }}" data-original="{{ $activity->name }}" required>
                    @error('name') <div>{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <select name="sport_id"  data-original="{{ $activity->sport_id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        @foreach($sports as $sport)
                            <option value="{{ $sport->id }}"
                                {{ old('sport_id', $activity->sport_id) == $sport->id ? 'selected' : '' }}>
                                {{ $sport->name }}
                            </option>
                        @endforeach      
                    </select>
                     @error('sport_id') <div>{{ $message }}</div> @enderror
                </div>

                <button class="btn btn-primary mt-2">{{ $activity->exists ? 'Actualizar' : 'Crear' }}</button>
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