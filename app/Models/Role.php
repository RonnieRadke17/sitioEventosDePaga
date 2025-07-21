<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;//hace un "borrado suave" de los registros eliminados

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name',];

    public function users()
    {
        return $this->hasMany(User::class);
    }


    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
