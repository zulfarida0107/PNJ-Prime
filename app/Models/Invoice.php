<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'order_id',
        'dpp',
        'discount_b2b',
        'shipping_cost',
        'ppn_amount',
        'pph23_amount',
        'emeterai_amount',
        'grand_total',
        'va_number',
        'payment_method',
        'payment_status',
        'due_at',
        'paid_at',
        'qr_pass_code',
    ];

    protected function casts(): array
    {
        return [
            'dpp' => 'decimal:2',
            'discount_b2b' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'ppn_amount' => 'decimal:2',
            'pph23_amount' => 'decimal:2',
            'emeterai_amount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'due_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
