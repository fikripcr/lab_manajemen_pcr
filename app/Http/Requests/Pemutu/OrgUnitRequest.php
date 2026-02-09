<?php
namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class OrgUnitRequest extends FormRequest
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
        return [
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:org_unit,orgunit_id',
            'type'      => 'nullable|string|max:100',
            'code'      => 'nullable|string|max:50',
            'seq'       => 'nullable|integer',
        ];
    }
}
