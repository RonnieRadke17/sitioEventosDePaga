<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'sport_id', 'mix'];

    //relation with sport
    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    //relation with event
    public function eventActivities()
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }

    /**
     * Relación con el modelo ActivityEventUser (Uno a Muchos)
     */
    public function activityEventUsers()
    {
        return $this->hasMany(ActivityEventUser::class);
    }

}
