<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Http\Controllers\Controller as Controller;

class MailTemplate extends Model
{
    use HasFactory;
    // use HasSlug;

    public $table = 'mail_templates';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'from_name',
        'subject',
        'category',
        'from_email',
        'replay_from_email',
        'slug',
        'message',
    ];

    public function getCategoryNameAttribute()
    {
        # code...
        return Controller::MailCategories($this->category, true);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
