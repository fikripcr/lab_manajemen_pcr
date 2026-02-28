<?php
namespace App\Http\Requests\Shared;

use App\Http\Requests\BaseRequest;

class StrukturOrganisasiRequest extends BaseRequest
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
            'parent_id'   => 'nullable|exists:struktur_organisasi,orgunit_id',
            'name'        => 'required|string|max:191',
            'code'        => 'nullable|string|max:50',
            'type'        => 'required|string',
            'description' => 'nullable|string',
            'is_active'   => 'sometimes|boolean',
            'color'       => 'nullable|string|max:20',
            'sort_order'  => 'nullable|integer',
            'seq'         => 'nullable|integer',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => $this->boolean('is_active'),
            ]);
        }
    }
}
