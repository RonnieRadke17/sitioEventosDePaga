@extends('layouts.app')
@section('title','Home')

@section('content')
<p>Pagina privada
    @auth
     {{Auth::user()->name}}
     {{Auth::user()->email}}
     {{Auth::user()->password}}
    @endauth
</p>

<p>eventos que hay</p>


<a href="{{route('logout')}}">Salir</a>    
@endsection

