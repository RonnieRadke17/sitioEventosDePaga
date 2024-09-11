@extends('layouts.app')

@section('content')

@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
{{-- cuenta regresiva de el tiempo restante y boton de reenviar correo--}}

<h2>tu cuenta esta suspendida</h2>
@endsection

