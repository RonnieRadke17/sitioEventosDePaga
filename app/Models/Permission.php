<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // hace un "borrado suave" de los registros eliminados

class Permission extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'name',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
