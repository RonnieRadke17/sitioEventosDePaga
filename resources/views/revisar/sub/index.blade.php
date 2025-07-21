<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <a href="{{url('sub/create')}}" class="btn btn-success">Registrar</a>
    <br>

    <h2>Lista de subs</h2>
    
    <ul>
        @forelse($subs as $sub)
        <li>
            {{$sub->name}} <a href="{{route('sub.edit', $sub->id)}}" class="btn btn-warning">Editar</a>
            <form action="{{route('sub.destroy', $sub->id)}}" method="POST">
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