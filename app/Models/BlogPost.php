<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class BlogPost extends Model
{
    use HasFactory;
    protected $table = 'blogposts';
    protected $fillable = [
        'title',
        'content',
        'author',
        'image',
        'slug',
        'is_published',
    ];
   
}

