<?php

namespace App\Casts;

use App\ValueObject\UserSettings;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class UserSettingsCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $data = $value ? json_decode($value, true) : [];
        return new UserSettings($data);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if(is_array($value)){
            $value = new UserSettings($value);
        }

        if(!$value instanceof UserSettings){
            throw new \InvalidArgumentException('$value must be an array of UserSettings objects');
        }

        return json_encode($value->toArray());
    }
}
