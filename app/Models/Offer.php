<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'offers';
    protected $primaryKey = 'offer_id';

    protected $fillable = [
        'title',
        'description',
        'discount_percentage',
        'start_date',
        'end_date',
        'terms_and_conditions',
        'status',
        'created_by',
        'updated_by',
        'offer_code',
        'offer_type',
        'maximum_discount',
        'minimum_purchase_amount',
        'applicable_to'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'discount_percentage' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'minimum_purchase_amount' => 'decimal:2'
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Define relationships here if any
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
