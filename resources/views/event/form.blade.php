<h1>{{$mode}} evento</h1>

@if(count($errors)>0)
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif

<input type="text" name="name" id="" value="{{ $event->name ?? '' }}">
        <input type="date" name="date" id="" value="{{ $event->date ?? '' }}">
        <input type="time" name="start_time" id="" value="{{ $event->start_time ?? '' }}">
        <input type="time" name="end_time" id="" value="{{ $event->end_time ?? '' }}">
        <input type="text" name="description" id="" value="{{ $event->description ?? '' }}">
        
        <label for="image">Imagen</label>
        @if(isset($event->image))
            <img class="img-thumbnail img-fluid" src="{{asset('storage').'/'.$event->image}}" width="100" alt="">
        @endif
        <input type="file" class="form-control" name="image" value="" id="imagen">

        <select class="form-control" name="status">
            <option value="Activo">Activo</option>
            
        </select>
        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required value="{{ $event->price ?? '' }}">
        <input type="submit" value="{{$mode}}">