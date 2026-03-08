<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;

class FragranceNote extends Model
{
    use HasTranslations;

    protected $fillable = ['product_id', 'type', 'note'];

    public $translatable = ['note'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
