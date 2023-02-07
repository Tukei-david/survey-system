<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\SlugOptions;

class Survey extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [ 'user_id', 'title' ,'slug', 'status', 'description', 'expire_date' ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
