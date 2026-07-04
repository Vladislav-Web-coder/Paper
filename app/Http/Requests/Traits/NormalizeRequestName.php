<?php

namespace App\Http\Requests\Traits;

use Illuminate\Support\Str;

trait NormalizeRequestName
{
    protected function prepareForValidation()
    {
        if($this->has('name')) {
            $name = $this->input('name');
            $normalizedName = Str::lower(Str::squish($name));

            $this->merge([$normalizedName]);
        }
    }
}
