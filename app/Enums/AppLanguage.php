<?php

namespace App\Enums;

enum AppLanguage: string
{
    case ENGLISH = 'en';
    case RUSSIAN = 'ru';
    case FRENCH = 'fr';

    public function label(): string
    {
        return match ($this) {
            self::ENGLISH => 'English',
            self::RUSSIAN => 'Русский',
            self::FRENCH => 'Français',
        };
    }

    public static  function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }
}
