<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
    use SoftDeletes;
    protected $fillable = ['product_id', 'type', 'quantity', 'reference'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
