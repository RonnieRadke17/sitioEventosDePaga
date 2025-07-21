<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // hace un "borrado suave" de los registros eliminados

class EventUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    // Definir el nombre de la tabla si no sigue la convención de Laravel
    protected $table = 'event_user';
    protected $fillable = [
        'user_id',
        'event_id',
    ];

    /**
     * Relación con el modelo ActivityEventUser (Uno a Muchos)
     */
    public function activityEventUsers()//table activity_event_user
    {
        return $this->hasMany(ActivityEventUser::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
