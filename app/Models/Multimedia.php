<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multimedia extends Model
{
    use HasFactory;
    protected $fillable = [
        'url',
        'event_id',
        'type'
    ];

    /* relación con event multimedia 1-m event */
    public function event(){
        return $this->belongsTo(Event::class);
    }

}
