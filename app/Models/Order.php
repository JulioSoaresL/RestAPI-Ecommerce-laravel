<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['tenant_id', 'customer_id', 'amount', 'shipping_address', 'status'])]
class Order extends Model
{
    use BelongsToTenant;

    protected $casts = [
        'shipping_address' => 'array',
        'status' => OrderStatusEnum::class,
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
