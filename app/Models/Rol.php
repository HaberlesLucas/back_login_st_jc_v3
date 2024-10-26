<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    //
    protected $table = 'rols';
    protected $primaryKey = 'id_rol';
    public $timestamps = true;
    protected $fillable = ['nombre'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
