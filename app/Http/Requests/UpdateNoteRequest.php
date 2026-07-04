<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\NormalizeRequestData;
use App\Models\Note;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
{
    use NormalizeRequestData;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $note = $this->route('note');

        if (!$note instanceof Note) {
            $note = Note::findOrFail($note);
        }

        return $this->user()->can('update', $note);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'folder_name' => 'sometimes|nullable|array',
            'folder_name.*' => 'string|max:50',
            'tags_name' => 'sometimes|nullable|array',
            'tags_name.*' => 'string|max:50',
        ];
    }
}
