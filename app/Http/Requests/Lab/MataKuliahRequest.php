<?php
namespace App\Http\Requests\Lab;

use Illuminate\Foundation\Http\FormRequest;

class MataKuliahRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $mataKuliahId = $this->route('mata_kuliah'); // Get the mata kuliah ID from the route for update operations

        return [
            'kode_mk' => $mataKuliahId ? 'required|string|max:20|unique:mata_kuliahs,kode_mk,' . $mataKuliahId : 'required|string|max:20|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks'     => 'required|integer|min:1|max:6',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return validation_messages_id();
    }
}
