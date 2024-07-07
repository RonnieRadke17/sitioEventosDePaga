<style>
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content label {
        display: block;
        padding: 8px;
        position: relative; /* Needed to correctly position sub-dropdown */
    }

    .dropdown-content label:hover {
        background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        background-color: #3e8e41;
    }

    .sub-dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 140px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        left: 160px; /* Adjust to align with parent dropdown */
        top: 0px;   /* Align with the parent label */
    }

    .dropdown-content label:hover .sub-dropdown-content {
        display: block;
    }
</style>

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

<input type="text" name="name" id="" value="{{ $event->name ?? '' }}" placeholder="Nombre del evento">
<br>
<input type="date" name="date" id="" value="{{ $event->date ?? '' }}">
<br>
<label for="start_time">Fecha del evento</label>
<input type="time" name="start_time" id="" value="{{ $event->start_time ?? '' }}">
<br>
<label for="end_time">cierre de inscripciones</label>
<input type="time" name="end_time" id="" value="{{ $event->end_time ?? '' }}">
<br>
<input type="text" name="description" id="" value="{{ $event->description ?? '' }}" placeholder="Descripción del evento">
<br>
<input type="number" name="capacity" id="" value="{{ $event->capacity ?? '' }}" placeholder="Capacidad del evento">
<br>
<input type="number" class="form-control" id="price" name="price" step="0.01" min="0"  value="{{ $event->price ?? '' }}" placeholder="Precio del evento">      
<br>

<label for="image">Imagen</label>
@if(isset($event->image))
    <img class="img-thumbnail img-fluid" src="{{asset('storage').'/'.$event->image}}" width="100" alt="">
@endif
<input type="file" class="form-control" name="images[]" value="" id="imagen" multiple>
<br>       
@if($mode == 'Editar')
<select class="form-control" name="status">
    <option value="Activo" {{ $event->status == 'Activo' ? 'selected' : '' }}>Activo</option>
    <option value="Inactivo" {{ $event->status == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
</select>
<br>
@endif

<!-- Tabla de actividades -->
<table class="table table-light">
    <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Genero y Subs</th>
        </tr>
    </thead>
    <tbody>
        @foreach($activities as $activity)
        <tr>
            <td><input type="checkbox" name="selected_activities[]" value="{{ $activity->id }}" {{ isset($eventActivities[$activity->id]) ? 'checked' : '' }}></td>
            <td>{{ $activity->name }}</td>
            <td>
                <div class="dropdown">
                    <input type="button" class="dropbtn" value="Seleccionar Genero y Subs"></input>
                    <div class="dropdown-content">
                        @foreach(['M', 'F', 'Mix'] as $gender)
                            <label>
                                <input type="checkbox" name="genders[{{ $activity->id }}][{{ $gender }}]" value="{{ $gender }}" 
                                @if(isset($eventActivities[$activity->id]) && $eventActivities[$activity->id]->contains('gender', $gender))
                                    checked
                                @endif> {{ $gender }}
                                <div class="sub-dropdown-content">
                                    @foreach ($subs as $sub)
                                        <label>
                                            <input type="checkbox" name="subs[{{ $activity->id }}][{{ $gender }}][]" value="{{ $sub->id }}"
                                            @if(isset($eventActivities[$activity->id]))
                                                @foreach($eventActivities[$activity->id] as $eventActivity)
                                                    @if($eventActivity->gender == $gender && $eventActivity->sub_id == $sub->id)
                                                        checked
                                                    @endif
                                                @endforeach
                                            @endif> {{ $sub->name }} ({{ $gender }})
                                        </label>
                                    @endforeach
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-------aqui van dos mapas el cual uno es para entrega de kits y otro es para lugar del evento--------->
<input type="submit" value="{{$mode}}">
