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
    
}
