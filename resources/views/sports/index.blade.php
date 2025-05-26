@extends('layouts.app')
@section('title', 'Deportes')
@section('head')
    <style>
        .scroll-container {
            max-height: 60vh; /* Ajusta esto según sea necesario */
            overflow-y: auto;
        }
    </style>
@endsection

@section('content')
    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                {{-- success --}}
                <div id="alert-additional-content-3" class="p-4 mb-4 text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
                    <div class="flex items-center">
                        <svg class="shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="sr-only">Info</span>
                        <h3 class="text-4xl font-medium">Success</h3>
                    </div>
                    <div class="mt-2 mb-4 text-lg">
                        {{ session('success') }}
                    </div>
                    <div class="flex">
                        <button type="button" class="text-green-800 bg-transparent border border-green-800 hover:bg-green-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-base px-3 py-1.5 text-center dark:hover:bg-green-600 dark:border-green-600 dark:text-green-400 dark:hover:text-white dark:focus:ring-green-800" data-dismiss-target="#alert-additional-content-3" aria-label="Close">
                        Dismiss
                        </button>
                    </div>
                </div>
            @endif

            @if (session('error'))
            {{-- error --}}
                <div id="alert-additional-content-error" class="p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
                    <div class="flex items-center">
                        <svg class="shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="sr-only">Error</span>
                        <h3 class="text-4xl font-medium">Error</h3>
                    </div>
                    <div class="mt-2 mb-4 text-lg">
                        {{ session('error') }}
                    </div>
                    <div class="flex">
                        <button type="button" class="text-red-800 bg-transparent border border-red-800 hover:bg-red-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-base px-3 py-1.5 text-center dark:hover:bg-red-600 dark:border-red-600 dark:text-red-400 dark:hover:text-white dark:focus:ring-red-800" data-dismiss-target="#alert-additional-content-error" aria-label="Close">
                            Dismiss
                        </button>
                    </div>
                </div>
            @endif

            {{-- boton de creación --}}
            <div data-dial-init class="fixed end-6 bottom-6 group" data-modal-target="form-modal" id="create" data-modal-toggle="form-modal">
                <div id="speed-dial-menu-default" class="flex flex-col items-center hidden mb-4 space-y-2">
                </div>
                <button type="button" data-dial-toggle="speed-dial-menu-default" aria-controls="speed-dial-menu-default" aria-expanded="false" class="flex items-center justify-center text-white bg-blue-700 rounded-full w-14 h-14 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800">
                    <svg class="w-5 h-5 transition-transform group-hover:rotate-45" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                    </svg>
                    <span class="sr-only">Open actions menu</span>
                </button>
            </div>

            <!-- Main modal -->
            <div id="form-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Registro de deporte
                            </h3>
                            <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="form-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5">
                            <form class="space-y-4" id="formSport" method="post">
                            @csrf
                            <x-forms.input-text name="name" description="Nombre del deporte" oldvalue="{{ $sport->name ?? ''}}" oninput="validateName()" onblur="validateName()" required></x-forms.input-text>
                            
                            <select name="category_id" id="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Selecciona una categoría</option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div id="categoryError" class="text-red-500 text-sm">
                                @error('category_id')
                                    {{ $message }}
                                @enderror
                            </div>


                            <x-forms.submit-button value="Guardar"></x-forms.submit-button> 
                            </form>
                        </div>
                    </div>
                </div>
            </div> 


            {{-- tabla --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Deportes registrados</h3>

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full table-fixed text-sm text-left text-gray-500 dark:text-gray-400">

                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">Categoría</th>
                                <th scope="col" class="px-6 py-3">Creado</th>
                                <th scope="col" class="px-6 py-3">Actualizado</th>
                                <th scope="col" class="w-auto">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sports as $sport)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $sport->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $sport->category->name ?? 'Sin categoría' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $sport->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $sport->updated_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-2 py-4 space-x-2">
                                        <a href="{{ route('sports.show', $sport->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Deshabilitar</a>
                                        <button 
                                            class="font-medium text-green-600 dark:text-green-400 hover:underline edit-btn" 
                                            data-id="{{ $sport->id }}" 
                                            data-name="{{ $sport->name }}" 
                                            data-category="{{ $sport->category_id }}" 
                                            data-modal-toggle="form-modal"
                                        >Editar</button>
                                        <form action="{{ route('sports.destroy', $sport->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 dark:text-red-400 hover:underline" onclick="return confirm('¿Estás seguro de eliminarlo?')">Eliminar</button>
                                        </form>
                                        {{-- <select name="category_id" id="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-auto p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="" disabled selected>Acciones</option>
                                                <option value="edit">Editar</option>
                                                <option value="edit">Editar</option>
                                        </select> --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                        No tienes deportes registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

                {{-- paginador --}}
                <div class="mt-4">
                    {{ $sports->links() }}
                </div>

                {{-- paginador --}}
                {{-- <div class="mt-4 flex justify-between">
                    @if ($sports->onFirstPage())
                        <span class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500">« Anterior</span>
                    @else
                        <a href="{{ $sports->previousPageUrl() }}" class="px-4 py-2 text-sm text-blue-600 hover:underline">« Anterior</a>
                    @endif

                    @if ($sports->hasMorePages())
                        <a href="{{ $sports->nextPageUrl() }}" class="px-4 py-2 text-sm text-blue-600 hover:underline">Siguiente »</a>
                    @else
                        <span class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500">Siguiente »</span>
                    @endif
                </div> --}}
        </div>
    </div>
   
<script>
  function validateName() {
    const nameInput = document.getElementById('name');
    const errorDiv = document.getElementById('nameError');
    const value = nameInput.value.trim();
    
    if (!value) {
      errorDiv.textContent = 'El nombre es obligatorio.';
      nameInput.classList.add('invalid');
      nameInput.classList.remove('valid');
    } else if (value.length < 5) {
      errorDiv.textContent = 'El nombre debe tener al menos 5 caracteres.';
      nameInput.classList.add('invalid');
      nameInput.classList.remove('valid');
    } else if (value.length > 60) {
      errorDiv.textContent = 'El nombre no debe exceder los 60 caracteres.';
      nameInput.classList.add('invalid');
      nameInput.classList.remove('valid');
    } else {
      errorDiv.textContent = '';
      nameInput.classList.remove('invalid');
      nameInput.classList.add('valid');
    }
  }
</script>









<script>
    window.onload = function() {
        /* codigo para mostrar el modal abierto si hay error en el back */
        let nameErrorMessage = document.getElementById('nameError')?.innerText.trim();
        let categoryErrorMessage = document.getElementById('categoryError')?.innerText.trim();
            if (nameErrorMessage || categoryErrorMessage) {
                // Mostrar el modal y solucion temporal debido a que no muestra los colores adecuados hasta cierto punto
                const modal = document.getElementById('form-modal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

        const createButton = document.getElementById('create');
        //const editButton = document.getElementById('edit');
        const form = document.getElementById('formSport');
    
        //verificar si el botón y el formulario existen
        if (createButton && form) {
            createButton.addEventListener('click', function() {
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) {
                    methodInput.remove();
                }

                form.action = "{{ route('sports.store') }}";
                console.log(form.action);
                
                // Limpiar los campos del formulario
                /* document.getElementById('name').value = '';
                document.getElementById('category_id').value = '';
                document.getElementById('nameError').innerText = '';
                document.getElementById('categoryError').innerText = ''; */

                form.reset();
            });
        }

        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const category = this.dataset.category;

                let methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    form.appendChild(methodInput);
                }
                methodInput.value = 'PATCH';

                form.querySelector('input[name="name"]').value = name;
                form.querySelector('select[name="category_id"]').value = category;

                form.action = "{{ url('sports') }}/" + id;
                //falta abajo del token el csrf y el method
                console.log(form.action);
            });
        });
    };
</script>

@endsection