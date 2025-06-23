<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;//hace un "borrado suave" de los registros eliminados

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;
    //data falta el lugar y costo
    protected $fillable = [
        'name',
        'description',
        'event_date',
        'registration_deadline', 
        'capacity',
        'status',
        'price'
    ];

    //relations with users,place,payments

    public function users()//relation many to many with events table EventUser
    {
        return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id')->withTimestamps(); 
        // Agrega withTimestamps si quieres que los timestamps de la tabla pivote estén disponibles
    }

    public function categories()//relation with categories m-m está bien
    {
        return $this->belongsToMany(Category::class, 'category_event');
    }

    //relation with place m-m
    public function places()//está bien
    {
        return $this->belongsToMany(Place::class);
    }

    //relation with images 1-m está bien
    public function images()
    {
        return $this->hasMany(Image::class);
    }


    public function user()//ya
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function payments()//está bien
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

    public function eventUser()//relacion que permite mostrar solo los eventos que tienen capacidad
    {
        return $this->hasMany(EventUser::class, 'event_id');
    }

}
