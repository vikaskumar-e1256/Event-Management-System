<?php

namespace Modules\Payment\Enums;

enum PaymentStatus: string
{
    public const PENDING = 'pending';
    public const SUCCESS = 'completed';
    public const FAILED = 'failed';
    public const REFUNDED = 'refunded';


    public static function all(): array
    {
        return [
            self::PENDING,
            self::SUCCESS,
            self::FAILED,
            self::REFUNDED,
        ];
    }
}
