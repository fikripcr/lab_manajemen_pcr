<?php
namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class IndikatorStandarRequest extends FormRequest
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
        if ($this->route('indikator')) {
            // Probably storeAssignment
            return [
                'personil_id'  => 'required|exists:pegawai,pegawai_id',
                'year'         => 'required|integer',
                'semester'     => 'required|integer',
                'target_value' => 'nullable|string',
                'weight'       => 'nullable|numeric|min:0',
            ];
        }

        return [
            'doksub_id' => 'required|exists:pemutu_dok_sub,doksub_id',
            'indikator' => 'required|string',
            'target'    => 'required|string',
            'type'      => 'required|in:standar,performa',
            'parent_id' => 'nullable|exists:pemutu_indikator,indikator_id',
        ];
    }
}
