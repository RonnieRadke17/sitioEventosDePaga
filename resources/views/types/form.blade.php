@extends('layouts.app')
@section('content')
     <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="POST" action="{{ $type->exists ? route('types.update', $id) : route('types.store') }}">
                @csrf
                @if($type->exists) 
                    @method('PUT') 
                    <input type="hidden" name="id" value="{{ $type->exists ? $id : '' }}">
                @endif
                <div class="form-group">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $type->name) }}" required>
                    @error('name') <div>{{ $message }}</div> @enderror
                </div>

                <button class="btn btn-primary mt-2">{{ $type->exists ? 'Actualizar' : 'Crear' }}</button>
            </form>
        </div>
    </div>
@endsection