<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;//hace un "borrado suave" de los registros eliminados

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = ['name'];

    //relation with activities m-m table category_event
    public function events()
    {
        return $this->belongsToMany(Event::class, 'category_event');
    }
}
