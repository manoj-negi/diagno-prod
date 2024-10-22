<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    // Define the table name if it doesn't follow Laravel's naming convention (i.e., plural form)
    protected $table = 'cart';

    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'user_id',
        'doctor_id',
        'hospital_id',
        'test_id',
        'test_type',
        'date',
        'time',
        'prescription_file',
        'member_id',
        'patient_address',
        'payment_mode',
        'booking_amount',
        'offer_id',
        'discounted_amount',
        'status',
    ];
    public $timestamps = true;

    // Define relationships

    /**
     * The user who owns the cart (one-to-many).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The doctor associated with the cart (optional, one-to-many).
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * The hospital associated with the cart (one-to-many).
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * The test associated with the cart (one-to-one or one-to-many).
     */
    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }

    /**
     * The offer applied to the cart (optional, one-to-one).
     */
    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
    // Add additional functions or accessors if needed
}
