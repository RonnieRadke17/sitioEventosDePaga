<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <a href="{{url('activity/create')}}" class="btn btn-success">Registrar</a>
    <br>

    <h2>Lista de actividades</h2>
    
    <ul>
        @forelse($activities as $activity)
        <li>
            {{$activity->name}} 
            @if($activity->mix == 1)
            <span>(mix)</span>
            @endif
            <a href="{{route('activity.edit', $activity->id)}}" class="btn btn-warning">Editar</a>
            <form action="{{route('activity.destroy', $activity->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Eliminar</button>
            </form>
        </li>
        @empty
        <li>No hay actividades creadas aun</li>
    @endforelse
    </ul>

    
</div>
</body>
</html>