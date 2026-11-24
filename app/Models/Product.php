<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'unit_id',
        'category_id',
        'sub_category_id',
        'submitted_by',
        'code',
        'name',
        'description',
        'order_type',
        'hpp',
        'price_b2c',
        'price_b2b',
        'stock',
        'image_path',
        'status',
        'curation_notes',
    ];

    protected function casts(): array
    {
        return [
            'hpp' => 'decimal:2',
            'price_b2c' => 'decimal:2',
            'price_b2b' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(PortalUser::class, 'submitted_by');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
