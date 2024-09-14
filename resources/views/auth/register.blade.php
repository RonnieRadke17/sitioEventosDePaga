@extends('layouts.app') 
@section('title','Registro')
@section('content')

<!-- Mostrar errores de validación -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Mostrar mensaje de error de sesión (como el error de código de verificación no expirado) -->
{{ session()->get('error') }}

<form method="POST" action="{{ route('process-register') }}">
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

    <!-- Fecha de nacimiento -->
    <div>
        <label for="birthdate">Fecha de nacimiento</label>
        <input id="birthdate" type="date" name="birthdate" value="{{ old('birthdate') }}" required>
        @error('birthdate')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Género -->
    <div class="mb-4">
        <label class="block text-gray-700">Género</label>
        <div class="flex items-center">
            <input type="radio" name="gender" id="male" value="M" class="mr-2" {{ old('gender') == 'M' ? 'checked' : '' }}>
            <label for="male" class="mr-4">Masculino</label>

            <input type="radio" name="gender" id="female" value="F" class="mr-2" {{ old('gender') == 'F' ? 'checked' : '' }}>
            <label for="female">Femenino</label>
        </div>
        @error('gender')
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
