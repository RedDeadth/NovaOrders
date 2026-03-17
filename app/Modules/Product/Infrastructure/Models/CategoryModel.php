<?php

namespace App\Modules\Product\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryModel extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'slug',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(ProductModel::class, 'category_id');
    }
}
