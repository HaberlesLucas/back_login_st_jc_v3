<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'dni';
    public $incrementing = false;
    protected $keyType = 'bigInteger';

    protected $fillable = [
        'dni',
        'apellido_nombre',
        'estado',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'rol_user', 'dni', 'id_rol');
    }

    public function getApellidoNombre()
    {
        return $this->apellido_nombre;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'apellido_nombre' => $this->apellido_nombre,
        ];
    }
}
