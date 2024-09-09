@extends('layouts.app')

@section('content')

@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
{{-- cuenta regresiva de el tiempo restante --}}

<form action="{{ route('password.send-password-code') }}" method="post">
    @csrf
    <input type="email" name="email" placeholder="ingrese su correo">
    <button type="submit">reenviar codigo</button>
</form>
@endsection

