@extends('layouts.app')
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
@endsection