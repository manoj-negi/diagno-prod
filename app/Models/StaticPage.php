<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    use HasFactory;
    protected $table = "static_pages";


    protected $fillable = [
       'title',
       'spenish_title',
       'slug',
       'spenish_slug',
       'content',
       'spenish_content',
       'status',
       'meta_keyword',
       'spenish_meta_keyword',
       'meta_title',
       'spenish_meta_title',
       'meta_description',
       'spenish_meta_description',
    ];

}