@extends('layouts.app')
@section('content')
<form method="POST" action="{{ $multimedia !=null ? route('event-multimedia.update', $id) : route('event-multimedia.store') }}" enctype="multipart/form-data">
@csrf

@if($multimedia != null) 
    @method('PUT') 
@endif

<input type="hidden" name="event_id" value="{{ $event->id }}">

@if ($cover)
    {{-- imagen actual --}}
    <input type="file" name="cover" id="coverInput" class="hidden" accept="image/*" onchange="previewImage(event)">

    <div onclick="document.getElementById('coverInput').click()" class="relative w-64 h-32 cursor-pointer group">
        <img id="coverPreview" src="{{ asset('storage/' . $cover->url) }}" alt="Imagen de portada"
            class="w-full h-full object-cover rounded hover:opacity-70 transition">
        <div
            class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 text-white text-sm font-semibold opacity-0 group-hover:opacity-100 transition">
            Click para cambiar imagen
        </div>
    </div>

@else
    <p class="text-gray-500">No hay imagen de portada disponible.</p>
    <div class="flex items-center justify-center w-full">
        <label for="cover" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                </svg>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
            </div>
            <input id="cover" type="file" class="hidden" name="cover" />
            @error('cover') <div>{{ $message }}</div> @enderror
        </label>
    </div>
@endif


@if($haskit)

    @if($kit)
        {{-- kit actual --}}
        <input type="file" name="kit" id="kitInput" class="hidden" accept="image/*" onchange="previewImage(event)">

        <div onclick="document.getElementById('kitInput').click()" class="relative w-64 h-32 cursor-pointer group">
            <img id="kitPreview" src="{{ asset('storage/' . $kit->url) }}" alt="Imagen del kit"
                class="w-full h-full object-cover rounded hover:opacity-70 transition">
            <div
                class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 text-white text-sm font-semibold opacity-0 group-hover:opacity-100 transition">
                Click para cambiar imagen
            </div>
        </div>

    @else
        <div class="flex items-center justify-center w-full">
        <label for="kit" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                </svg>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
            </div>
            <input id="kit" type="file" class="hidden" name="kit" />
            @error('kit') <div>{{ $message }}</div> @enderror
        </label>
    </div>
    @endif

@else
    <p class="text-red-500">No hay kit de entrega disponible para este evento.</p>      
@endif

<div class="flex items-center justify-center w-full">
    <label for="content" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
        <div class="flex flex-col items-center justify-center pt-5 pb-6">
            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
            </svg>
            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
        </div>
        <input id="content" type="file" class="hidden" name="content[]" multiple/>
        @error('content') <div>{{ $message }}</div> @enderror
    </label>
</div>


    <button class="btn btn-primary mt-2">{{ $multimedia != null ? 'Actualizar' : 'Crear' }}</button>
    
</form>    


{{-- links de redes sociales videos o algo por el estilo --}}

<script>
    function previewImage(event) {
        const input = event.target;
        let previewId = '';

        // Detectar cuál input fue activado
        if (input.id === 'coverInput') {
            previewId = 'coverPreview';
        } else if (input.id === 'kitInput') {
            previewId = 'kitPreview';
        }

        const preview = document.getElementById(previewId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>





@endsection