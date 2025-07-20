@extends('layouts.app')
@section('content')
     <div class="row justify-content-center">
        <div class="col-md-4">
            <form method="POST" action="{{ $activity->exists ? route('activites.update', $id) : route('activites.store') }}">
                @csrf
                @if($activity->exists) 
                    @method('PUT') 
                    <input type="hidden" name="id" value="{{ $activity->exists ? $id : '' }}">
                @endif
                <div class="form-group">
                    <label>Name</label>
                    <input name="name" class="form-control" value="{{ old('name', $activity->name) }}" required>
                    @error('name') <div>{{ $message }}</div> @enderror
                </div>
                <div class="form-group">{{-- selector del deporte --}}
                    
                </div>

                <button class="btn btn-primary mt-2">{{ $activity->exists ? 'Actualizar' : 'Crear' }}</button>
            </form>
        </div>
    </div>
@endsection