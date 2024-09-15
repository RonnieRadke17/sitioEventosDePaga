<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sub extends Model
{
    use HasFactory;

     protected $fillable = ['name'];

    public function subs()
    {
        return $this->hasMany(ActivityEvent::class);
    }

    // Definir la relación de uno a muchos
    public function activityEventUsers()
    {
        return $this->hasMany(ActivityEventUser::class, 'sub_id', 'id');
    }

}
