@extends('layouts.app')

@section('content')
<div class="container">

@if ($errors->any())
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        @foreach ($errors->all() as $error)
            <span class="block font-medium">{{ $error }}</span>
        @endforeach
    </div>
@endif



<h1>Listado de actividades</h1>

<div data-dial-init class="fixed right-6 bottom-6">
    <a href="{{ route('activities.create') }}" data-dial-toggle="speed-dial-menu-dropdown-alternative" aria-controls="speed-dial-menu-dropdown-alternative" aria-expanded="false" class="flex items-center justify-center ml-auto text-white bg-blue-700 rounded-full w-14 h-14 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none dark:focus:ring-blue-800">
        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="m13.835 7.578-.005.007-7.137 7.137 2.139 2.138 7.143-7.142-2.14-2.14Zm-10.696 3.59 2.139 2.14 7.138-7.137.007-.005-2.141-2.141-7.143 7.143Zm1.433 4.261L2 12.852.051 18.684a1 1 0 0 0 1.265 1.264L7.147 18l-2.575-2.571Zm14.249-14.25a4.03 4.03 0 0 0-5.693 0L11.7 2.611 17.389 8.3l1.432-1.432a4.029 4.029 0 0 0 0-5.689Z"/>
        </svg>
    </a>
</div>




<form class="max-w-sm mx-auto">
  <label for="typeSelector" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccionar opción</label>
  <select id="typeSelector" onchange="window.location.href = this.value"
    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    
    <option value="{{ route('activities.content', 'active') }}" {{ ($type ?? 'active') == 'active' ? 'selected' : '' }}>
      Registros Activos
    </option>

    <option value="{{ route('activities.content', 'trashed') }}" {{ ($type ?? '') == 'trashed' ? 'selected' : '' }}>
      Registros Inactivos
    </option>

    <option value="{{ route('activities.content', 'all') }}" {{ ($type ?? '') == 'all' ? 'selected' : '' }}>
      Todos
    </option>
  </select>
</form>






<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Nombre
                </th>
            @if ($type == 'trashed' || $type == 'all')
                <th>Eliminado el</th>{{-- aqui poner icono de basura --}}
            @endif
                <th scope="col" class="px-6 py-3">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activities as $activity)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$activity->name}}
                </td>
                <td class="px-6 py-4 text-right">
                    {{ ($activity->trashed() || $type == 'all') 
                    ? ($activity->deleted_at ? $activity->deleted_at->format('d/m/Y H:i') : 'No aplica') 
                    : '' }}
                </td>
                
                <td class="px-6 py-4 text-right">
                    @if($activity->trashed())
                        <form 
                            action="{{ route('activities.restore', $activity->encrypted_id) }}" 
                            method="POST" 
                            style="display:inline;"
                            >
                            @csrf
                            <button class="btn btn-sm btn-outline-success">
                                Activar
                            </button>
                        </form>

                        <form 
                            action="{{ route('activities.forceDelete', $activity->encrypted_id) }}" 
                            method="POST" 
                            class="d-inline"
                            onsubmit="return confirm('¿Seguro de eliminar?')"
                            >
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                        </form>
                    @else
                        <a href="{{ route('activities.edit', $activity->encrypted_id) }}" class="btn btn-sm btn-outline-secondary">
                        Editar
                        </a>
                        <form 
                        action="{{route('activities.destroy', $activity->encrypted_id)}}" 
                        method="POST" 
                        class="d-inline"
                        onsubmit="return confirm('¿Seguro de desactivar?')"
                        >
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Desactivar</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

</div>
@endsection
