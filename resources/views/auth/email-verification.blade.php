@extends('layouts.app')
@section('content')
@if (session('message'))
    <p>{{ session('message') }}</p>
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


<p>Hola: {{ $user['name'] }}</p>

te hemos mandado un codigo de verificacion al correo 
<p>Email: {{ $user['email'] }}</p>
ingresa el codigo de verificacion
<form action="{{ route('check-email-verification') }}" method="POST">
    @csrf
    <input type="number" name="code">
    <input type="submit" value="Verificar">
</form>

aqui falta poner un contador que marque los 5 minutos en cuenta regresiva y 
el boton de volver a mandar el token//ya

<form action="{{ route('send-verification-code') }}" method="POST" id="resendForm">
    @csrf
    <button type="submit" >Reenviar código</button>
</form>

@endsection