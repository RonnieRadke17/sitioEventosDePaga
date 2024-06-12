<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    //data falta el lugar y costo
    protected $fillable = ['name', 'date', 'start_time', 'end_time', 'description','status','price'];

    //relations with users,place,payments

    public function user()//ya
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function payments()
    {
        return $this->belongsToMany(User::class, 'payments')->withTimestamps();
    }


    //relation with activity 
    public function eventActivities()
    {
        return $this->belongsToMany(Activity::class)->withTimestamps();
    }


    //relation with images 1-n-1
    public function imgEvents()
    {
        return $this->belongsToMany(Image::class)->withTimestamps();
    }
    
}
