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
        'kit_delivery', //kit de eventos kitdeliveri 
        'capacity',
        'status',
        'price',
        'place_id', //llave foranea de la tabla places
    ];

    //relations with users,place,payments

    public function users()//relation many to many with events table EventUser
    {
        return $this->belongsToMany(User::class); 
    }

    public function categories()//relation with categories m-m está bien
    {
        return $this->belongsToMany(Category::class, 'category_event');
    }

    //relation with place 1-m
    public function place()//está bien
    {
        return $this->belongsTo(Place::class);
    }

    //relation with images 1-m está bien
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    //relación con multimedia event m-1 multimedia
    public function multimedias()
    {
        return $this->hasMany(Multimedia::class);
    }

    public function payments()//está bien
    {
        return $this->belongsToMany(User::class, 'payments')->withTimestamps();
    }

    //no sirve esta relación
    public function eventUsers()//relacion que permite mostrar solo los eventos que tienen capacidad
    {
        return $this->hasMany(EventUser::class, 'event_id');
    }
    
    //relation with activity_events table
    public function activityEvents()
    {
        return $this->hasMany(ActivityEvent::class, 'event_id');
    }
}
