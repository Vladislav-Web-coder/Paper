<?php

namespace App\Enums;

enum SettingsTab: string
{
    case INTERFACE = 'interface';
    case NOTIFICATIONS = 'notifications';
    case PRIVACY = 'privacy';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
