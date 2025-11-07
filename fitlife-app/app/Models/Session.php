<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id', 'room_id', 'coach_id', 'start_time', 'end_time', 'max_participants'
    ];

    /**
     * Get the room that owns the session.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the coach that owns the session.
     */
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    /**
     * Get the activity that owns the session.
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
