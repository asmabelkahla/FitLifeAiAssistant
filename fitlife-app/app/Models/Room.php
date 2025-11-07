<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['name', 'capacity', 'status'];

    /**
     * Les équipements associés à la salle.
     */
    public function equipments()
    {
        return $this->belongsToMany(Equipment::class);
    }
}
