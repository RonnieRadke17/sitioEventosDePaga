<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;//hace un "borrado suave" de los registros eliminados

class Sub extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name'];

    //retation with ActivityEvent m-m-
    public function activityEvents()
    {
        return $this->hasMany(ActivityEvent::class, 'sub_id');
    }

    // Definir la relación de uno a muchos
    public function activityEventUsers()
    {
        //version more clear
        return $this->hasMany(ActivityEventUser::class);

        //return $this->hasMany(ActivityEventUser::class, 'sub_id', 'id');
    }

}
