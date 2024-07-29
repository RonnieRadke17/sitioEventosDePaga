<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
     'name',
     'activity_category_id'
    ];

    //relation with event
    public function eventActivities()
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }

    //one to many relationship
    public function activityCategory()
    {
        return $this->belongsTo(ActivityCategory::class);
    }


}
