<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionInventory extends Model
{
    use HasFactory;
    protected $table = "subscription_inventories";
    protected $primaryKey = "id";

    protected $fillable = [
        'subscription_id ',
        'user_id ',
        'transection_id',
        'payer_id',
        'payer_email',
        'amount',
        'currency',
        'pay_status',
        'status',
        'start_date',
        'end_date'
    ];
   
    public function subscriptions()
    {
        return $this->hasOne(Subscription::class,'id','subscription_id');
    }
}
