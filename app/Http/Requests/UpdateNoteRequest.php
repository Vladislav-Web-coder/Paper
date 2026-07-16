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
    protected function prepareForValidation(): void
    {
        if(!$this->input('folders')) {
            $this->merge([
                'folders' => [],
            ]);
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
            'name' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string|max:2295',
            'folders' => 'sometimes|nullable|array',
            'folders.*' => 'string|max:50',
            'tags_name' => 'sometimes|nullable|array',
            'tags_name.*' => 'string|max:50',
        ];
    }
}
