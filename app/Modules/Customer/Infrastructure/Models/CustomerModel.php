<?php

namespace App\Modules\Customer\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerModel extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'document_number',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(\App\Modules\Order\Infrastructure\Models\OrderModel::class, 'customer_id');
    }
}
