@extends('layouts.app')
@section('content')
@if (request()->has('message'))
    <div class="alert alert-warning">
        {{ request('message') }}
    </div>
@endif

{{-- boton de volver a mandar codigo si ya no es valido el anterior, como un contador de cuanto tiempo queda--}}
@endsection

