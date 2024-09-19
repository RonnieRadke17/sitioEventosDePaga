@extends('layouts.app')
@section('content')
@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

@if (session('error'))
    <p style="color: red;">{{ session('error') }}</p>
@endif

@if($orderedImages->isNotEmpty())
<div >
    @foreach($orderedImages as $image)
        <img  src="{{ asset('storage/' . $image->image) }}" alt="Event Image">
    @endforeach
</div>
@endif
<h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $event->name }}</h1>
<p class="text-3xl tracking-tight text-gray-900">
    {{ $event->price ? '$' . $event->price : 'Gratis' }}
</p>

<div class="mt-10">
    <h3 class="text-xl font-medium text-gray-900">Descripcion</h3>
    <div class="space-y-6">
        <p class="text-base text-gray-900">{{$event->description}}</p>
    </div>
</div>

    <form action="{{ route('paypal') }}" method="post">
        @csrf
        <input type="hidden" name="event" value="{{encrypt($event->id)}}"> 
        <button type="submit" class="mt-10 flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Pay with PayPal</button>
    </form>

    <a href="{{ route('stripe.form',encrypt($event->id)) }}" class="mt-10 flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Pago con Tarjeta</a>
    

@endsection

