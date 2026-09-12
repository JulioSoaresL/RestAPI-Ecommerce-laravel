<?php

namespace App\Enums;

enum OrderStatusEnum: int
{
    case PENDING = 0;
    case PAID = 1;
    case SHIPPED = 2;
    case CANCELED = 3;

    public static function make(int|string|null $value): ?self
    {
        if ($value === null) return null;

        return self::tryFrom((int) $value);
    }

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::SHIPPED => 'Shipped',
            self::CANCELED => 'Canceled',
        };
    }

    public function toArray(): array
    {
        return [
            'code' => $this->value,
            'label' => $this->label(),
        ];
    }
}
