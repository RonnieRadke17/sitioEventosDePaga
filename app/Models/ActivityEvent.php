<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//suse Illuminate\Database\Eloquent\SoftDeletes; // hace un "borrado suave" de los registros eliminados

class ActivityEvent extends Model
{
    use HasFactory;
    //use SoftDeletes;

    protected $fillable = [
        'event_id','activity_id','gender','sub_id'
    ];
    
    //creo falta la relacion con la tabla 'events' ya esta agregada un protopype de la relación
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Relación con la tabla 'activities'
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    // Relación con la tabla 'subs'
    public function sub()
    {
        return $this->belongsTo(Sub::class, 'sub_id');
    }
}
