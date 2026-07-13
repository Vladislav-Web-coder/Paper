<?php

namespace App\Enums;

enum NotificationChannel: string
{
    case MAIL = 'mail';
    case TELEGRAM = 'telegram';
    case DATABASE = 'database';
}
