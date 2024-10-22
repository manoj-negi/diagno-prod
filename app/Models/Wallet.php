<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $table = "wallet";
    protected $primaryKey = "id";

    protected $fillable = [
        'user_id',
        'wallet_balance',
        'status',
        'credit',
        'debit'
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class)->where('status', 1);
    }

    public function subscriptionInventory()
    {
        return $this->belongsToMany(SubscriptionInventory::class, 'subscription_inventories', 'doctor_id', 'subscription_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
