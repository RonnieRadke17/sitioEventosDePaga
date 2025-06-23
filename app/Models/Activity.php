<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;//hace un "borrado suave" de los registros eliminados


class Activity extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = ['name','sport_id'];

    //relation with sport 1-m table activities
    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    //relation with types m-m table activity_type
    public function types()
    {
        return $this->belongsToMany(Type::class, 'activity_type');
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
