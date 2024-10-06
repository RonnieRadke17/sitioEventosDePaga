@extends('layouts.app')
@section('title','Evento')
@section('content')
@if ($errors->has('error'))
    <div class="alert alert-danger">
        {{ $errors->first('error') }}
    </div>
@endif
@if ($errors->has('activities'))
    <div class="alert alert-danger">
        {{ $errors->first('activities') }}
    </div>
@endif
@if ($errors->has('event'))
    <div class="alert alert-danger">
        {{ $errors->first('event') }}
    </div>
@endif
<div class="bg-white">
    <!-- Product info -->
    <div class="mx-auto max-w-7xl px-4 pb-16 pt-10 sm:px-6 lg:grid lg:grid-cols-3 lg:gap-x-8 lg:px-8 lg:pb-24 lg:pt-16">
        {{-- Columna de datos del evento --}}
        <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 lg:pb-16 lg:pr-8 lg:pt-6">
            <!-- Description and details -->
            <!-- Carousel for Event and Kit Images -->
            <div id="demo" class="carousel slide" data-bs-ride="carousel">
                <!-- Indicators/dots -->
                <div class="carousel-indicators">
                    @foreach($orderedImages as $index => $image)
                        <button type="button" data-bs-target="#demo" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></button>
                    @endforeach
                </div>

                <!-- The slideshow/carousel -->
                <div class="carousel-inner">
                    @foreach($orderedImages as $index => $image)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image->image) }}" class="d-block w-100" alt="Event Image">
                        </div>
                    @endforeach
                </div>

                <!-- Left and right controls/icons -->
                <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="lg:col-span-1 mt-4 lg:mt-0 lg:pl-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $event->name }}</h1>
            <p class="text-3xl tracking-tight text-gray-900">
                {{ $event->price ? '$' . $event->price : 'Gratis' }}
            </p>
            @if($event->price)
                <form class="mt-10" method="POST" action="{{ route('events.confirmPayment', encrypt($event->id)) }}">
                    @csrf         
                    @if($activities->isNotEmpty())
                        <h3>Actividades del Evento</h3>
                        <ul>
                            @foreach($activities as $activityEvent)
                                <li>
                                    <label>
                                        <input type="checkbox" name="activities[{{ encrypt($activityEvent->activity->id) }}][{{ encrypt($activityEvent->gender) }}][{{ encrypt($activityEvent->sub_id) }}]">
                                        <label>{{ $activityEvent->activity->name }} </label>
                                        @if ($activityEvent->gender === 'Mix')
                                            Mix
                                        @endif
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No hay actividades disponibles para este evento.</p>
                    @endif
                    <p class="text-base tracking-tight text-gray-900">Seleccione un metodo de pago</p>
                    <button type="submit" class="mt-10 flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Comprar</button>
                </form>
            @else
                <form class="mt-10" method="POST" action="{{ route('events.inscriptionFree', encrypt($event->id)) }}">
                    @csrf         
                    @if($activities->isNotEmpty())
                        <h3>Actividades del Evento</h3>
                        <ul>
                            @foreach($activities as $activityEvent)
                                <li>
                                    <label>
                                        <input type="checkbox" name="activities[{{ encrypt($activityEvent->activity->id) }}][{{ encrypt($activityEvent->gender) }}][{{ encrypt($activityEvent->sub_id) }}]">
                                        <label>{{ $activityEvent->activity->name }} </label>
                                        @if ($activityEvent->gender === 'Mix')
                                            Mix
                                        @endif
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No hay actividades disponibles para este evento.</p>
                    @endif

                    <button type="submit" class="mt-10 flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Inscribirme
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Descripción, Lugar y Actividades en una fila -->
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="bg-gray-100 p-6 rounded-lg shadow-lg grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Descripción del Evento -->
            <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Descripción del Evento</h3>
                <p class="text-gray-700">{{ $event->description }}</p>
                <h4 class="mt-4 text-lg font-semibold text-gray-800">Lugar del Evento</h4>
                <p class="text-gray-600">{{ $event->location ?? 'No especificado' }}</p>
                <h4 class="mt-4 text-lg font-semibold text-gray-800">Fecha del Evento</h4>
                <p class="text-gray-600">{{ $event->event_date }}</p>
                <h4 class="mt-4 text-lg font-semibold text-gray-800">Entrega de Kit</h4>
                <p class="text-gray-600">{{ $event->kit_delivery }}</p>
                <h4 class="mt-4 text-lg font-semibold text-gray-800">Fecha Límite de Registro</h4>
                <p class="text-gray-600">{{ $event->registration_deadline }}</p>
            </div>

            <!-- Lugares Relacionados y Actividades -->
            <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Actividades del Evento</h3>
                @if($activities->isNotEmpty())
                    <ul class="list-disc list-inside">
                        @foreach($activities as $activityEvent)
                            <li>
                                <strong class="text-gray-800">Actividad:</strong> {{ $activityEvent->activity->name }}<br>
                                <strong class="text-gray-800">Género:</strong> {{ $activityEvent->gender }}<br>
                                <strong class="text-gray-800">Subcategoría:</strong> {{ $activityEvent->sub->name }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No hay actividades disponibles para este evento.</p>
                @endif
                <h3 class="text-2xl font-bold text-gray-800 mb-4 mt-8">Lugares Relacionados</h3>
                <ul class="list-disc list-inside">
                    @foreach($places as $place)
                        <li>
                            <strong class="text-gray-800">{{ $place->name }}</strong><br>
                            {{ $place->address }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
