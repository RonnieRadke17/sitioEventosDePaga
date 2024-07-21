@extends('layouts.app')
@section('title','Restablecer contraseña')
@section('content')
@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div>
        <label for="password">Nueva contraseña</label>
        <input type="password" name="password" required>
    </div>

    <div>
        <label for="password_confirmation">Confirmar nueva contraseña</label>
        <input type="password" name="password_confirmation" required>
    </div>

    <div>
        <button type="submit">Restablecer contraseña</button>
    </div>
</form>

@endsection


