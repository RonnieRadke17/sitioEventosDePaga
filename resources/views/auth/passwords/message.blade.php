@extends('layouts.app')
@section('content')
@if (request()->has('message'))
    <div class="alert alert-warning">
        {{ request('message') }}
    </div>
@endif

@if (request()->has('status_code'))
    <div class="alert alert-info">
        Código de estado: {{ request('status_code') }}
    </div>
@endif



{{-- boton de volver a mandar codigo si ya no es valido el anterior, como un contador de cuanto tiempo queda--}}
@endsection

