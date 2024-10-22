<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages_model extends Model
{
    use HasFactory;
    protected $table="pages";
    protected $primarykey = "id";

    protected $fillable = [
        'title',
        'banner_section',
        'content',
        'slug'

    ];
}
