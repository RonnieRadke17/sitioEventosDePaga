<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventActivity extends Model
{
    use HasFactory;

    public function subs()
    {
        return $this->belongsTo(Sub::class);
    }
}
