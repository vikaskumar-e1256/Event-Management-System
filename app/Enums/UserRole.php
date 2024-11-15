<?php

namespace App\Enums;

enum UserRole: int
{
    case ATTENDEE = 0;
    case ORGANIZER = 1;

    public static function getAllRoles(): array
    {
        return [
            self::ATTENDEE->value => 'Attendee',
            self::ORGANIZER->value => 'Organizer',
        ];
    }
}
