<form action="{{route('activity.update', $activity->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
   @include('activities.form',['mode'=>'Editar'])
</form>