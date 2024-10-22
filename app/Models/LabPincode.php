<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabPincode extends Model
{
    use HasFactory;

    protected $table = 'lab_pincode';

    protected $fillable = [
        'pincode_id', // Reference to the pincode table
        'lab_id',     // Add lab_id as required
    ];

    // Define the relationship with Pincode
    public function pincode()
    {
        return $this->belongsTo(Pincode::class);
    }


    public function lab()
    {
        return $this->belongsTo(User::class, 'lab_id');
    }
}
