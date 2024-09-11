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

@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

{{-- cuenta regresiva de el tiempo restante y boton de reenviar correo--}}

<h2>tu cuenta esta suspendida</h2>
<form action="{{ route('password.send-code') }}" method="post">
    @csrf
    <button type="submit">enviar codigo</button>
</form>

@endsection

