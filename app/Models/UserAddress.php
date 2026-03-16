<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'first_name',
        'last_name',
        'phone',
        'email',
        'city',
        'state',
        'address_line1',
        'address_line2',
        'zip_code',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
