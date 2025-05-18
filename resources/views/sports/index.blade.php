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


{{-- Tabla: Dispositivos propios del usuario --}}
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Deportes registrados</h3>

                <div class="w-full overflow-x-auto">
                    <div class="shadow border border-gray-200 dark:border-gray-700 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($sports as $sport)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $sport->id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $sport->name }}</td>
                                        <td class="px-6 py-4 text-sm font-medium flex space-x-3">
                                            {{-- Ver --}}
                                            <a href="{{ route('sports.show', $sport->id) }}" title="Ver">
                                                <svg class="h-5 w-5 text-indigo-500 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            {{-- Editar --}}
                                            <button title="Editar"  data-modal-toggle="form-modal" class="edit-btn"  data-id="{{ $sport->id }}" data-name="{{ $sport->name }}" data-category="{{ $sport->category_id }}">
                                                <svg class="h-5 w-5 text-green-500 hover:text-green-700 dark:text-green-300 dark:hover:text-green-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487a1.875 1.875 0 112.65 2.65L7.5 18.15H4v-3.5L16.862 3.487z" />
                                                </svg>
                                            </button> 
                                            {{-- Eliminar --}}
                                            <form action="{{ route('sports.destroy', $sport->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminarlo?')" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Eliminar">
                                                    <svg class="h-5 w-5 text-red-500 hover:text-red-700 dark:text-red-300 dark:hover:text-red-100" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                            No tienes dispositivos registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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