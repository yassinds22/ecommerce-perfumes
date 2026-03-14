<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Translatable\HasTranslations;

class FragranceNote extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = ['name', 'description'];

    public $translatable = ['name', 'description'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_fragrance_note')
                    ->withPivot('type')
                    ->withTimestamps();
    }

}
