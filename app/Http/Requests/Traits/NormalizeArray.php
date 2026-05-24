<?php

namespace App\Http\Requests\Traits;

use Illuminate\Support\Str;

trait NormalizeArray
{
    protected function normalizeArray(array $array): array
    {
        return collect($array)
            ->map(fn ($item) => Str::lower(Str::squish($item)))
            ->filter(fn($item) => $item !== '' && $item !== null)
            ->unique()
            ->values()
            ->toArray();

    }
}
