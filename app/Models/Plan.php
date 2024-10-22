<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $table='plans';
    protected $primarykey='id';
    protected $fillable=[
        'title',
        'commission_percentage',
        'validity',
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
