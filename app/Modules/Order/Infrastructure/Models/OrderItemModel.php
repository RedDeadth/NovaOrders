<?php

namespace App\Modules\Order\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemModel extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderModel::class, 'order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Product\Infrastructure\Models\ProductModel::class, 'product_id');
    }
}
