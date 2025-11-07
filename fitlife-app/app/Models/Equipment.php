<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = ['name', 'room_id'];
    
    // Si vous utilisez une table avec un nom différent, spécifiez le nom de la table
    protected $table = 'equipments';
}

