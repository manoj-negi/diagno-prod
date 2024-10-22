<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    use HasFactory;
    protected $table='offer';
    protected $primarykey='id';
    protected $fillable=[
        'offer_name',
        'price',
        'status',
        'validity',
        'validity_end',
        'description',
        'image',
    ];

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class);
    }

    public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }
    

}
