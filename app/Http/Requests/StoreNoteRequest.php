<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\NormalizeRequestData;
use App\Models\Note;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
{
    use NormalizeRequestData;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Note::class);
    }
    protected function prepareForValidation()
    {
        $existingTags = $this->input('tags_name', []);
        $newTags = $this->input('new_tags', [], '');

        $filterTags = collect(explode(',', $newTags))
            ->map(fn($tag) => trim($tag))
            ->filter()
            ->unique()
            ->toArray();

        $tags = array_unique(array_merge($existingTags, $filterTags));
        $this->merge([
            'tags_name' => empty($tags) ? null : $tags,
        ]);

        $existingFolders = $this->input('folder_name', []);
        $newFolders = $this->input('new_folders', '');

        $filterFolders = collect(explode(',', $newFolders))
            ->map(fn($tag) => trim($tag))
            ->filter()
            ->unique()
            ->toArray();

        $folders = array_unique(array_merge($existingFolders, $filterFolders));
        $this->merge([
            'folder_name' => empty($folders) ? null : $folders,
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:2295',
            'folder_name' => 'nullable|array',
            'folder_name.*' => 'string|max:50',
            'tags_name' => 'nullable|array',
            'tags_name.*' => 'string|max:50',
        ];
    }
}
