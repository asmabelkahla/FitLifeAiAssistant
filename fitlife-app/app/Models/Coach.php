<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
    ];
  
    protected $table = 'coaches';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'coach_skill');
    }
}
