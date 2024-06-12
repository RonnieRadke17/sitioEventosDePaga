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
        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0"  value="{{ $event->price ?? '' }}">
        
        <br>
    <table class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Genero</th>
                <th>Sub</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
            <tr>
                <td><input type="checkbox" name="selected" id=""></td>
                <td>{{$activity->name}}</td>
                <td> 
                    <div class="dropdown">
                        <input type="button" class="dropbtn" value="Genero"></input>
                        <div class="dropdown-content">
                            <label>
                                <input type="checkbox" name="genders[]" value="M"> M
                            </label>
                            <label>
                                <input type="checkbox" name="genders[]" value="F"> F
                            </label>
                            <label>
                                <input type="checkbox" name="genders[]" value="Mix"> Mix
                            </label>
                        </div>
                    </div>
                </td>
                <td> 
                    <div class="dropdown">
                        <input type="button" class="dropbtn" value="sub"></input>
                        <div class="dropdown-content">
                            @foreach ($subs as $sub)
                                <label>
                                    <input type="checkbox" name="subs[]" value="{{ $sub->id }}"> {{ $sub->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>





        <input type="submit" value="{{$mode}}">