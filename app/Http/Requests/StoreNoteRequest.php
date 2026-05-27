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
