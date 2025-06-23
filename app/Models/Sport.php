<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;//hace un "borrado suave" de los registros eliminados

class Sport extends Model
{
    use SoftDeletes;
    protected $fillable = ['name'];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
