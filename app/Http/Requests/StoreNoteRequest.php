<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\NormalizeArray;
use App\Models\Note;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
{
    use NormalizeArray;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Note::class);
    }

    public function prepareForValidation(): void
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
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'folder_name' => 'nullable|array',
            'folder_name.*' => 'string|max:50',
            'tags_name' => 'nullable|array',
            'tags_name.*' => 'string|max:50',
        ];
    }
}
