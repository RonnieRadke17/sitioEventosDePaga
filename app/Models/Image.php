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
        'event_id',
        'type',
    ];


    //relation with events 1-m
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
