<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // hace un "borrado suave" de los registros eliminados

class ActivityEventUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'activity_event_user';

    protected $fillable = [
        'event_user_id',
        'activity_id',
        'gender',
        'sub_id',
    ];

     /**
     * Relación con el modelo EventUser (Muchos a Uno)
     */
    public function eventUser()//verificada correctamente
    {
        return $this->belongsTo(EventUser::class);
    }

    /**
     * Relación con el modelo Activity (Muchos a Uno)
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    //relacion con el modelo sub relacion de 
    // Definir la relación inversa, de muchos a uno
    public function sub()
    {
        return $this->belongsTo(Sub::class, 'sub_id', 'id');
    }


}
