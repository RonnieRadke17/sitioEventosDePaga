<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;//hace un "borrado suave" de los registros eliminados

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'paternal',
        'maternal',
        'birthdate',
        'gender',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //relations with payments
    public function payments()
    {
        return $this->belongsToMany(Event::class, 'payments')->withTimestamps();
    }

    // Relación con el modelo Role//verificada correctamente
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function events()//relation many to many with events table EventUser
    {
        return $this->belongsToMany(User::class,);
    }
}
