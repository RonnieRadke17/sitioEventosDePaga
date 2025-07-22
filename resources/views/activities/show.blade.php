@extends('layouts.app')
@section('content')
    {{$activity->name}} <br>
    {{$activity->sport->name}} <br>
    {{$activity->created_at->format('d/m/Y H:i')}}  <br>
    {{$activity->updated_at->format('d/m/Y H:i')}} <br>
    @if($activity->trashed())
        <p>Desactivada el {{$activity->deleted_at->format('d/m/Y H:i')}}</p>
    @endif    
@endsection