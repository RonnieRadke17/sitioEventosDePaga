@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ $dependency->exists ? route('dependencies.update', $id) : route('dependencies.store') }}">
                @csrf
                @if($dependency->exists) 
                    @method('PUT') 
                    <input type="hidden" name="id" value="{{ $dependency->exists ? $id : '' }}">
                @endif
                <div class="form-group">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $dependency->name) }}" required>
                    @error('name') <div>{{ $message }}</div> @enderror
                </div>

                <button class="btn btn-primary mt-2">{{ $dependency->exists ? 'Actualizar' : 'Crear' }}</button>
            </form>
@endsection