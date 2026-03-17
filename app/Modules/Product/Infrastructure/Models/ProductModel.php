<?php

namespace App\Modules\Product\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductModel extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'sku',
        'image_url',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryModel::class, 'category_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(\App\Modules\Order\Infrastructure\Models\OrderItemModel::class, 'product_id');
    }
}
