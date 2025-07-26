@extends('layouts.app')
@section('content')
    {{$sub->name}} <br>
    {{$sub->min}} <br>
    {{$sub->max}} <br>
    {{$sub->created_at->format('d/m/Y H:i')}}  <br>
    {{$sub->updated_at->format('d/m/Y H:i')}} <br>
    @if($sub->trashed())
        <p>Desactivada el {{$sub->deleted_at->format('d/m/Y H:i')}}</p>
    @endif    
@endsection