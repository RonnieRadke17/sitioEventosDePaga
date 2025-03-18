<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'lat',
        'lon'
    ];

    //relationship with event m-m    
    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}
