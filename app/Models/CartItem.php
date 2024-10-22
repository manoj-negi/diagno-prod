<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'test_id',
        'test_type',
    ];

    /**
     * Get the cart that owns the CartItem.
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the test associated with the cart item.
     */
    public function test()
    {
        return $this->belongsTo(LabTestName::class, 'test_id');
    }
}
