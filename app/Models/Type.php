<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;//hace un "borrado suave" de los registros eliminados

class Type extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name'];
    //relation with activities m-m table activity_type
     public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_type');
    }
}
