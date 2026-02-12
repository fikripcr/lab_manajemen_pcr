<?php
namespace App\Http\Requests\Hr;

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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'type'        => 'required|string',
            'parent_id'   => 'nullable|exists:hr_org_unit,org_unit_id',
            'code'        => 'nullable|string|max:50',
            'is_active'   => 'nullable|boolean',
            'description' => 'nullable|string',
        ];
    }
}
