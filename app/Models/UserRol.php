<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRol extends Model
{
    protected $table = 'rol_user';


    protected $fillable = [
        'dni',
        'id_rol'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'dni');
    }
}
