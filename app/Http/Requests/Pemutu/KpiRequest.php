<?php
namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class KpiRequest extends FormRequest
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
        if ($this->route('kpi')) {
            // Probably storeAssignment
            return [
                'kpi_assign' => 'required|array',
            ];
        }

        return [
            'parent_id' => 'required',
            'items'     => 'required|array|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'parent_id.required' => 'Parent Indikator wajib dipilih.',
            'items.required'     => 'Minimal satu sasaran kinerja harus diisi.',
            'items.min'          => 'Minimal satu sasaran kinerja harus diisi.',
        ];
    }
}
