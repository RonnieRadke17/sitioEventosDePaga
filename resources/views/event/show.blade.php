@extends('layouts.app')
@section('title','Evento')
@section('content')

<div class="bg-white py-16 px-6 lg:py-24 lg:px-8">
    <!-- Información del Producto -->
    <div class="mx-auto max-w-2xl lg:max-w-7xl lg:grid lg:grid-cols-3 lg:gap-x-8 lg:items-start">
        <!-- Carrusel de Imágenes y Descripción del Evento -->
        <div class="lg:col-span-2 lg:border-r lg:border-gray-200 lg:pr-8">
            <div class="mb-10">
                <h1 class="text-3xl font-bold tracking-tight text-blue-900">{{ $event->name }}</h1>
                <p class="text-xl text-blue-700 mt-2">{{ $event->description }}</p>
            </div>
            <!-- Carrusel de Imágenes -->
            <div id="demo" class="carousel slide rounded-lg shadow-lg overflow-hidden mb-10" data-bs-ride="carousel">
                <!-- Indicadores -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
                </div>
                <!-- Slides -->
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="https://c4.wallpaperflare.com/wallpaper/863/547/576/cristiano-ronaldo-real-madrid-2014-wallpaper-thumb.jpg" class="d-block w-100" alt="Slide 1">
                    </div>
                    <div class="carousel-item">
                        <img src="https://m.media-amazon.com/images/I/61fcnYK93bL._AC_UF894,1000_QL80_.jpg" class="d-block w-100" alt="Slide 2">
                    </div>
                    <div class="carousel-item">
                        <img src="https://4kwallpapers.com/images/wallpapers/cristiano-ronaldo-2560x1440-9595.jpg" class="d-block w-100" alt="Slide 3">
                    </div>
                </div>
                <!-- Controles -->
                <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>

        <!-- Información de Pago e Inscripción -->
        <div class="lg:col-span-1 mt-10 lg:mt-0 lg:pl-8">
            <h2 class="text-2xl font-bold text-blue-900">Información de Inscripción</h2>
            <p class="text-3xl font-bold text-blue-700 mt-4">
                {{ $event->price ? '$' . $event->price : 'Gratis' }}
            </p>
            @if($event->price)
                <form method="POST" action="{{ route('events.confirmPayment', encrypt($event->id)) }}" class="mt-8">
                    @csrf
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-blue-900">Actividades del Evento</h3>
                        <ul class="list-disc pl-5 mt-2">
                            @foreach($activities as $activityEvent)
                                <li class="text-base text-gray-700">
                                    <label>
                                        <input type="checkbox" name="activities[{{ encrypt($activityEvent->activity->id) }}][{{ encrypt($activityEvent->gender) }}][{{ encrypt($activityEvent->sub_id) }}]" class="mr-2">
                                        {{ $activityEvent->activity->name }} - 
                                        @if ($activityEvent->gender === 'Mix')
                                            Mix
                                        @endif
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">Comprar</button>
                </form>
            @else
                <form method="POST" action="{{ route('events.inscriptionFree', encrypt($event->id)) }}" class="mt-8">
                    @csrf
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-blue-900">Actividades del Evento</h3>
                        <ul class="list-disc pl-5 mt-2">
                            @foreach($activities as $activityEvent)
                                <li class="text-base text-gray-700">
                                    <label>
                                        <input type="checkbox" name="activities[{{ encrypt($activityEvent->activity->id) }}][{{ encrypt($activityEvent->gender) }}][{{ encrypt($activityEvent->sub_id) }}]" class="mr-2">
                                        {{ $activityEvent->activity->name }} - 
                                        @if ($activityEvent->gender === 'Mix')
                                            Mix
                                        @endif
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">Inscribirme</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Sección combinada de Detalles del Evento, Actividades y Lugares -->
    <div class="bg-white mx-auto p-8 rounded-lg shadow-lg max-w-4xl mt-16">
        <!-- Detalles del Evento -->
        <div class="bg-gray-50 p-4 mb-8 rounded-lg shadow">
            <h2 class="text-2xl font-bold text-blue-600 mb-4">Detalles del Evento</h2>
            <ul class="list-none space-y-2">
                <li><strong class="text-blue-800">Fecha del Evento:</strong> {{$event->event_date}}</li>
                <li><strong class="text-blue-800">Entrega de Kit:</strong> {{$event->kit_delivery}}</li>
                <li><strong class="text-blue-800">Fecha Límite de Registro:</strong> {{$event->registration_deadline}}</li>
                <li><strong class="text-blue-800">Lugar:</strong> {{ $place->name ?? 'No especificado'}}</li>
            </ul>
        </div>

        <!-- Actividades del Evento -->
        <div class="bg-gray-50 p-4 mb-8 rounded-lg shadow">
            <h2 class="text-2xl font-bold text-blue-600 mb-4">Actividades del Evento</h2>
            @if($activities->isNotEmpty())
                <ul class="list-disc list-inside space-y-2">
                    @foreach($activities as $activityEvent)
                        <li>
                            <strong class="text-blue-800">Actividad:</strong> {{ $activityEvent->activity->name }}<br>
                            <strong class="text-blue-800">Género:</strong> {{ $activityEvent->gender }}<br>
                            <strong class="text-blue-800">Subcategoría:</strong> {{ $activityEvent->sub->name }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">No hay actividades disponibles para este evento.</p>
            @endif
        </div>

        <!-- Lugares Relacionados -->
        <div class="bg-gray-50 p-4 mb-8 rounded-lg shadow">
            <h2 class="text-2xl font-bold text-blue-600 mb-4">Lugares Relacionados</h2>
            @if($places->isNotEmpty())
                <ul class="list-disc list-inside space-y-2">
                    @foreach($places as $place)
                        <li>
                            <strong class="text-blue-800">{{ $place->name }}</strong><br>
                            {{ $place->address }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600">No hay lugares relacionados para este evento.</p>
            @endif
        </div>

        <!-- Imágenes del Evento -->
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <h2 class="text-2xl font-bold text-blue-600 mb-4">Imágenes del Evento</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($orderedImages->isNotEmpty())
                    @foreach($orderedImages as $image)
                        <img class="rounded-lg shadow-lg" src="{{ asset('storage/' . $image->image) }}" alt="Event Image">
                    @endforeach
                @else
                    <img class="w-full h-48 object-cover rounded-lg shadow-lg" src="{{ asset('storage/default.jpg') }}" alt="Default Image">
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
