<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'user_id',
        'segment',
        'order_type',
        'status',
        'booking_date',
        'booking_slot',
        'attachment_path',
        'notes',
        'shipping_address',
        'estimasi_hari',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'estimasi_hari' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(PortalUser::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }
}
