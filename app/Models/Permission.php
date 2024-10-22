<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class Permission extends Model
{
    use HasFactory;
    protected $table='permissions';
    protected $primarykey='id';
    protected $fillable=[
        'permission'
    ];
    public function role()
    {
        return $this->belongsToMany(Role::class);
    }
}
