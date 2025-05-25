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
                <div class="mb-4 p-4 text-green-700 bg-green-100 dark:bg-green-900 dark:text-green-200 border border-green-200 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <button data-modal-target="form-modal" id="create" data-modal-toggle="form-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                Nuevo
            </button>

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
                            <x-forms.input-text name="name" description="Nombre del deporte" oldvalue="{{ $sport->name ?? ''}}"></x-forms.input-text>
                            
                            <label for="category_id">Categoría</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="" disabled selected>Selecciona una categoría</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            

                            <x-forms.submit-button value="Registrar"></x-forms.submit-button> 
                            </form>
                        </div>
                    </div>
                </div>
            </div> 



            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Deportes registrados</h3>

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">Categoría</th>
                                <th scope="col" class="px-6 py-3">Creado</th>
                                <th scope="col" class="px-6 py-3">Actualizado</th>
                                <th scope="col" class="px-2 py-3">Acciones</th>
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
                                        <a href="{{ route('sports.show', $sport->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Ver</a>
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

            
        </div>
    </div>

   
 


<script>
    window.onload = function() {
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
                // Opcional: limpiar campos del formulario
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