@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('signup') }}">
    @csrf
    <!-- Nombre -->
    <div>
        <label for="name">Nombre</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required>
        @error('name')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Apellido Paterno -->
    <div>
        <label for="paternal">Apellido Paterno</label>
        <input id="paternal" type="text" name="paternal" value="{{ old('paternal') }}" required>
        @error('paternal')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Apellido Materno -->
    <div>
        <label for="maternal">Apellido Materno</label>
        <input id="maternal" type="text" name="maternal" value="{{ old('maternal') }}" required>
        @error('maternal')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Edad -->
    <div>
        <label for="age">Edad</label>
        <input id="age" type="number" name="age" value="{{ old('age') }}" required>
        @error('age')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Correo Electrónico -->
    <div>
        <label for="email">Correo Electrónico</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Contraseña -->
    <div>
        <label for="password">Contraseña</label>
        <input id="password" type="password" name="password" required>
        @error('password')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Confirmar Contraseña -->
    <div>
        <label for="password_confirmation">Confirmar Contraseña</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>
    </div>

    <button type="submit">Registrar</button>
</form>


@endsection
