@extends('layouts.app')
@section('content')
@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

@if ($errors->has('message'))
            <div class="alert alert-danger">
                {{ $errors->first('message') }}
            </div>
@endif

aqui ingresa el correo del cual no recuerda la contrasena
y manda una ventana a ese correo

metodos
vista de ingreso de  ya 
metodo que manda el correo ya 
vista de correo que se le muestra al usuario en su bandeja ya

<form action="{{'send-passwod-code'}}" method="post">
    @csrf
    <input type="email" name="email" placeholder="ingrese su correo">
    <button type="submit">enviar codigo</button>
</form>



@endsection