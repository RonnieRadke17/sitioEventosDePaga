<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // hace un "borrado suave" de los registros eliminados

class UserToken extends Model
{
    use HasFactory;
    use SoftDeletes; // hace un "borrado suave" de los registros eliminados
    protected $fillable = [
        'email',
        'token',
        'ip_address',
        'expiration',
        'type',
       ];
}
