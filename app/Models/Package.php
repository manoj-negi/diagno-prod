<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $table='labs_packages';
    protected $primarykey='id';
    protected $fillable = [
        'amount',
        'price',
        'lab_id',
        'profile_id',
        'parent_id',
        'package_name',
        'TAT', 
        'no_of_parameters', 
        'parent_id',
        'is_frequently_booking',
        'is_lifestyle',
        'image',
        'description',
        'type',
        'updated_at',
        'created_at',
    ];
    public function labDetails()
    {
        return $this->hasOne(User::class, 'id', 'lab_id');
    }
    // public function profiles()
    // {
    //     return $this->belongsToMany(LabProfile::class, 'packages_profiles', 'profile_id', 'package_id');
    // }
    public function profiles()
{
    return $this->belongsToMany(LabProfile::class, 'labs_profile_packages', 'package_id', 'lab_profile_id');
}
    public function package()
    {
        return $this->hasOne(PackageProfile::class, 'profile_id', 'id');
    }
    public function amounts()
    {
        return $this->hasOne(LabSelectedPackages::class, 'package_id', 'id');
    }
    public static function getTests() 
    {
        $q = static::where('type','test')->orderBy('id','desc');
        return $q->get();
    }
    public static function getProfiles() 
    {
        $q = static::where('type','profile')->orderBy('id','desc');
        return $q->get();
    }
    public  function getChilds() 
    {
        return $this->belongsToMany(self::class, 'parent_childs', 'parent_id', 'child_id');
    }
    public function profile_name()
{
    return $this->belongsToMany(LabProfile::class, 'package_profile', 'package_id', 'profile_id');
}

public function labProfiles()
{
    return $this->belongsToMany(LabProfile::class, 'labs_profile_packages', 'package_id', 'lab_profile_id');
}
public function parentPackage()
    {
        return $this->belongsTo(Package::class, 'parent_id');
    }
    public function labsProfilePackage()
{
    return $this->hasOne(LabsProfilePackage::class, 'package_id', 'id');
}

public function childPackages()
{
    return $this->hasMany(Package::class, 'parent_id');
}
public function lab()
    {
        return $this->belongsTo(User::class, 'lab_id');
    }

}
