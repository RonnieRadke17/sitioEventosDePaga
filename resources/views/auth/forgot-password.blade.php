@extends('layouts.app')
@section('content')
@if (session('message'))
    
        {{ session('message') }}
    
@endif

@if ($errors->has('message'))
    {{ $errors->first('message') }}
            
@endif

olvido su contrasena?
<form action="{{'send-passwod-code'}}" method="post">
    @csrf
    <input type="email" name="email" placeholder="ingrese su correo">
    <button type="submit">enviar codigo</button>
</form>



@endsection