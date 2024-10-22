<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;
    protected $table='promo';
    protected $primarykey='id';
    protected $fillable=[
        'title',
        'lab_id',
        'promo_code',
        'validity',
        'validity_end',
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

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'lab_id');
    }
    

}
