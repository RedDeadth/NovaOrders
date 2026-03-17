<?php

namespace App\Modules\Order\Infrastructure\Models;

use App\Modules\Order\Domain\ValueObjects\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderModel extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'user_id',
        'status',
        'total',
        'notes',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'total' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Customer\Infrastructure\Models\CustomerModel::class, 'customer_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItemModel::class, 'order_id');
    }
}
