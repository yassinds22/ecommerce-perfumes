<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total',
        'shipping_cost',
        'tax',
        'status',
        'payment_method',
        'payment_status',
        'address_details',
        'shipping_company',
        'tracking_number',
        'shipped_at',
        'delivered_at'
    ];

    protected $casts = [
        'address_details' => 'array',
        'total' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
