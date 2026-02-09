<?php
namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class IndikatorRequest extends FormRequest
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
        $rules = [
            'indikator'       => 'required|string',
            'target'          => 'nullable|string',
            'jenis_indikator' => 'nullable|string|max:20',
            'labels'          => 'array',
            'org_units'       => 'array',
            'assignments'     => 'array',
        ];

        if ($this->isMethod('post')) {
            $rules['doksub_id'] = 'required|exists:dok_sub,doksub_id';
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['no_indikator']    = 'nullable|string|max:20';
            $rules['seq']             = 'nullable|integer';
            $rules['related_doksubs'] = 'array';
        }

        return $rules;
    }
}
