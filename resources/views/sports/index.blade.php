@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Listado de Deportes</h1>

    <a href="{{ route('sports.create') }}" class="btn btn-primary mb-3">Crear Deporte</a>

    <table class="table table-striped" id="sports-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="3">Cargando datos...</td></tr>
        </tbody>
    </table>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.querySelector('#sports-table tbody');
        fetch('/api/sports')
            .then(res => res.json())
            .then(data => {
                tbody.innerHTML = '';
                data.data.forEach(sport => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${sport.id}</td>
                        <td>${sport.name}</td>
                        <td>
                            <a href="/sports/${sport.id}" class="btn btn-sm btn-info">Ver</a>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error al cargar:', error);
                tbody.innerHTML = '<tr><td colspan="3">Error al cargar los deportes</td></tr>';
            });
    });
    </script>
</div>
@endsection
