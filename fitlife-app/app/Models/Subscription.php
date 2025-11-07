<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'subscription_type_id', 'status', 'start_date', 'end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function subscriptionType()
    {
        return $this->belongsTo(SubscriptionType::class);
    }
   
}
