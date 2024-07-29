<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
       ];
   


    //one to many relationship
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
