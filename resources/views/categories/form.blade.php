@extends('layouts.app')
@section('content')
     <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="POST" action="{{ $category->exists ? route('categories.update', $id) : route('categories.store') }}">
                @csrf
                @if($category->exists) 
                    @method('PUT') 
                    <input type="hidden" name="id" value="{{ $category->exists ? $id : '' }}">
                @endif
                <div class="form-group">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                    @error('name') <div>{{ $message }}</div> @enderror
                </div>

                <button class="btn btn-primary mt-2">{{ $category->exists ? 'Actualizar' : 'Crear' }}</button>
            </form>
        </div>
    </div>
@endsection