@extends('layouts.app')
@section('title','Evento')
@section('content')
{{-- falta poner la ubicacion las imgs del evento en un carrusel, las actividades y las tallas disponibles del kit--}}
<!-- Mostrar errores de validación -->
@if ($errors->has('error'))
    <div class="alert alert-danger">
        {{ $errors->first('error') }}
    </div>
@endif

<div class="bg-white">
      <!-- Product info -->
      <div class="mx-auto max-w-2xl px-4 pb-16 pt-10 sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:grid-rows-[auto,auto,1fr] lg:gap-x-8 lg:px-8 lg:pb-24 lg:pt-16">
        {{-- columna de datos del evento --}}
        <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 lg:pb-16 lg:pr-8 lg:pt-6">
          <!-- Description and details -->
          
          <div id="demo" class="carousel slide" data-bs-ride="carousel">

            <!-- Indicators/dots -->
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
              <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
              <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
            </div>
            
            <!-- The slideshow/carousel -->
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="https://c4.wallpaperflare.com/wallpaper/863/547/576/cristiano-ronaldo-real-madrid-2014-wallpaper-thumb.jpg" class="d-block" style="width:100%">
              </div>
              <div class="carousel-item">
                <img src="https://m.media-amazon.com/images/I/61fcnYK93bL._AC_UF894,1000_QL80_.jpg" class="d-block" style="height:200px,width:100%">
              </div>
              <div class="carousel-item">
                <img src="https://4kwallpapers.com/images/wallpapers/cristiano-ronaldo-2560x1440-9595.jpg" class="d-block" style="width:100%">
              </div>
            </div>
            
            <!-- Left and right controls/icons -->
            <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
              <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
              <span class="carousel-control-next-icon"></span>
            </button>
          </div>

          <div class="mt-10">
            <h3 class="text-xl font-medium text-gray-900">Descripcion</h3>
            <div class="space-y-6">
                <p class="text-base text-gray-900">{{$event->description}}</p>
            </div>
            <h3 class="text-xl font-medium text-gray-900">Lugar del evento</h3>
            <div class="space-y-6">
                <p class="text-base text-gray-900">{{$event->description}}</p>
            </div>

            <h2 class="text-base text-gray-900">Fecha y hora del evento:{{$event->event_date}}</h2>{{-- aqui las fechas --}}
            <h2 class="text-base text-gray-900">Entrega de kit:{{$event->kit_delivery}}</h2>{{-- aqui las fechas --}}
            <h2 class="text-base text-gray-900">Fecha límite de registro:{{$event->registration_deadline}}</h2>{{-- aqui las fechas --}}

          </div>

        </div>
  
        <div class="mt-4 lg:row-span-3 lg:mt-0">

          <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{ $event->name }}</h1>
          <p class="text-3xl tracking-tight text-gray-900">
            {{ $event->price ? '$' . $event->price : 'Gratis' }}
          </p>
            @if($event->price)
                <!-- Si el precio no es nulo, mostrar este formulario -->
                @if($activities->isNotEmpty())
                  <h3>Actividades del Evento</h3>
                  <ul>
                      @foreach($activities as $activityEvent)
                          <li>
                              <label>
                                  <input type="checkbox" name="activities[]" value="{{ $activityEvent->activity->id }}">
                                  {{ $activityEvent->activity->name }}
                              </label>
                          </li>
                      @endforeach
                  </ul>
              @else
                  <p>No hay actividades disponibles para este evento.</p>
              @endif

              <form class="mt-10">
                  <p class="text-base tracking-tight text-gray-900">Seleccione un metodo de pago</p>
                  <button type="submit" class="mt-10 flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Comprar</button>
              </form>

              <form action="{{ route('paypal') }}" method="post">
                  @csrf
                  <input type="hidden" name="price" value="{{$event->price}}">
                  <input type="hidden" name="product_name" value="{{$event->name}}">
                  <input type="hidden" name="quantity" value="1">
                  <input type="hidden" name="event" value="{{$event->id}}">
                  <button type="submit" class="mt-10 flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Pay with PayPal</button>
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
                                <input type="checkbox" name="activities[{{ encrypt($activityEvent->activity->id) }}][{{ $activityEvent->gender }}][{{ encrypt($activityEvent->sub_id) }}]">
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
    
</div>

<h2>Lugares Relacionados:</h2>
<ul>
    @foreach($places as $place)
        <li>{{ $place->name }} - {{ $place->address }}</li>
    @endforeach
</ul>


@if($orderedImages->isNotEmpty())
<!-- Bucle para mostrar las imágenes en el orden 'cover', 'kit', 'content' -->
<div >
    @foreach($orderedImages as $image)
        <img  src="{{ asset('storage/' . $image->image) }}" alt="Event Image">
    @endforeach
</div>
@else
<!-- Imagen por defecto si no tiene imágenes -->
<img class="w-full h-48 object-cover" src="{{ asset('storage/default.jpg') }}" alt="Default Image">
@endif


@endsection
