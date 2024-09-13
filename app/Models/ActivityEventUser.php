<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityEventUser extends Model
{
    use HasFactory;
    protected $table = 'activity_event_user';

    protected $fillable = [
        'event_user_id',
        'activity_id',
    ];

     /**
     * Relación con el modelo EventUser (Muchos a Uno)
     */
    public function eventUser()
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
}
