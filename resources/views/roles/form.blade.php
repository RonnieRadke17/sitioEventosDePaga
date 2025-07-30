@extends('layouts.app')
@section('content')
     <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="POST" action="{{ $role->exists ? route('roles.update', $id) : route('roles.store') }}">
                @csrf
                @if($role->exists) 
                    @method('PUT') 
                    <input type="hidden" name="id" value="{{ $role->exists ? $id : '' }}">
                @endif
                <div class="form-group">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $role->name) }}" required>
                    @error('name') <div>{{ $message }}</div> @enderror
                </div>

                <button class="btn btn-primary mt-2">{{ $role->exists ? 'Actualizar' : 'Crear' }}</button>
            </form>
        </div>
    </div>
@endsection