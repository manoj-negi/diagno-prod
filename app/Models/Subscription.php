<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $table = "subscriptions";
    protected $primaryKey = "id";

    protected $fillable = [
        'title',
        'days',
        'price',
        'days',
        'status'
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class)->where('status', 1);
    }

    public function subscriptionInventory()
    {
        return $this->belongsToMany(SubscriptionInventory::class, 'subscription_inventories', 'doctor_id', 'subscription_id');
    }
}
