<form action="{{route('event.update', $event->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
   @include('event.form',['mode'=>'Editar'])
</form>