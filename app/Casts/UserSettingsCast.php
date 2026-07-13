<?php

namespace App\Casts;

use App\ValueObject\UserSettings;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class UserSettingsCast implements CastsAttributes
{
    /**
     * Преобразует значение из базы данных в Value Object.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $data = $value ? json_decode($value, true) : [];

        return UserSettings::fromArray(is_array($data) ? $data : []);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof \App\ValueObject\UserSettings) {
            return json_encode($value->toArray());
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return null;
    }

}
