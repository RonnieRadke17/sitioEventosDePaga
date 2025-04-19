@extends('layouts.app')

@section('content')

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>
                    <button wire:click="edit({{ $category->id }})">Editar</button>
                    <form method="POST" action="{{ route('categories.destroy', $category) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


@endsection