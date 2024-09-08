@extends('layouts.app')

@section('content')

@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
{{-- cuenta regresiva de el tiempo restante --}}

<form action="" method="post">
    <input type="submit" value="Reenviar código">
</form>
@endsection

