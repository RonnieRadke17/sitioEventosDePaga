<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    //data falta el lugar y costo
    protected $fillable = [
        'name',
        'description',
        'event_date',
        'kit_delivery',
        'registration_deadline', 
        'is_limited_capacity',
        'capacity',
        'activities',
        'status',
        'price'
    ];

    //relations with users,place,payments

    //relation with place m-m
    public function places()
    {
        return $this->belongsToMany(Place::class);
    }


    
    public function user()//ya
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    //relacion de inscripcion del usuario a un evento es decir relacion con la tabla EventUser
   /*  public function users()
    {
        return $this->belongsToMany(User::class);
    } */
   /*  public function users()
{
    return $this->belongsToMany(User::class, 'user_events')->withTimestamps();
} */
// En el modelo Event.php
public function users()
{
    return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id')
                ->withTimestamps(); // Agrega withTimestamps si quieres que los timestamps de la tabla pivote estén disponibles
}


    /* public function place()
    {
        return $this->belongsTo(Place::class);
    } */

    public function payments()
    {
        return $this->belongsToMany(User::class, 'payments')->withTimestamps();
    }


    //relation with activity 
    public function eventActivities()
    {
        return $this->belongsToMany(Activity::class)->withTimestamps();
    }

    public function activityEvents()
    {
        return $this->hasMany(ActivityEvent::class, 'event_id');
    }


    //relation with images 1-m
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function eventUser()//relacion que permite mostrar solo los eventos que tienen capacidad
    {
        return $this->hasMany(EventUser::class, 'event_id');
    }

    
}
