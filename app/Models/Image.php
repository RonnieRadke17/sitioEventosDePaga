<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image',
    ];


    //relation with events 1-n-1
    public function imgEvents()
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }
}
