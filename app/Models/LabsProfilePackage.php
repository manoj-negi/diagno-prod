<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabsProfilePackage extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'labs_profile_packages';

    // Ensure 'id' is auto-incrementing
    public $incrementing = true;

    // Specify the primary key
    protected $primaryKey = 'id';

    // Allow mass assignment for specific columns
    protected $fillable = [
        'lab_profile_id', 
        'package_id'
    ];

    // Enable or disable timestamps (created_at and updated_at)
    public $timestamps = true;

    /**
     * Define the relationship with the LabProfile model.
     * Assuming you have a `LabProfile` model that this links to.
     */
    public function labProfile()
    {
        return $this->belongsTo(LabProfile::class, 'id','lab_profile_id');
    }

    /**
     * Define the relationship with the LabsPackage model.
     * Assuming you have a `LabsPackage` model that this links to.
     */
    public function package()
    {
        return $this->belongsTo(LabsPackage::class, 'id','package_id');
    }
    public function packageData()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
