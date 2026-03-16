<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia, SoftDeletes, HasFactory;

    protected $fillable = ['name', 'slug'];

    public $translatable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
