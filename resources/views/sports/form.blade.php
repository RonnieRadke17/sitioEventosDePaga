@extends('layouts.app')
<<<<<<< HEAD

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
=======
@section('content')

    <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="POST" action="{{ $sport->exists ? route('sports.update', $id) : route('sports.store') }}">
                @csrf
                @if($sport->exists) 
                    @method('PUT') 
                    <input type="hidden" name="id" value="{{ $sport->exists ? $id : '' }}">
                @endif
                <div class="form-group">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $sport->name) }}" required>
                    @error('name') <div>{{ $message }}</div> @enderror
                </div>

                <button class="btn btn-primary mt-2">{{ $sport->exists ? 'Actualizar' : 'Crear' }}</button>
            </form>
        </div>
    </div>
>>>>>>> a0a7cf16af904fe9b799689a3381af0f7a230214
@endsection