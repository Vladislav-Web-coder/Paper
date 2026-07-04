<?php

namespace App\Http\Requests\Traits;

use Illuminate\Support\Str;

trait NormalizeRequestData
{
    final protected function prepareForValidation()
    {
        $updates = [];
        if($this->has('tags_name')) {
            $tags_name = $this->input('tags_name');
            $updates['tags_name'] = $this->normalizeArray($tags_name);
        }
        if($this->has('folder_name')) {
            $folder_name = $this->input('folder_name');
            $updates['folder_name'] = $this->normalizeArray($folder_name);
        }
        if(!empty($updates)) {
            $this->merge($updates);
        }
    }
    final protected function normalizeArray(array $array): array
    {
        return collect($array)
            ->map(fn ($item) => Str::lower(Str::squish($item)))
            ->filter(fn($item) => $item !== '' && $item !== null)
            ->unique()
            ->values()
            ->toArray();
    }
}
