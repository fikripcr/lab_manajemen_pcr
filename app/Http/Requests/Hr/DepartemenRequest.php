<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class DepartemenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'departemen' => 'required|string|max:255',
            'alias'      => 'nullable|string|max:50',
            'abbr'       => 'nullable|string|max:20',
            // 'jurusan_id' => 'nullable|exists:hr_jurusan,jurusan_id', // Uncomment if juridiction exists/needed
            'is_active'  => 'nullable|boolean',
        ];
    }
}
