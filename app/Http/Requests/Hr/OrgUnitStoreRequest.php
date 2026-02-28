<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class OrgUnitStoreRequest extends BaseRequest
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
            'parent_id'   => 'nullable|exists:struktur_organisasi,orgunit_id',
            'level'       => 'nullable|integer|min:1',
            'sort_order'  => 'nullable|integer|min:1',
            'is_active'   => 'nullable|boolean',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'Nama Unit',
            'type'        => 'Tipe Unit',
            'parent_id'   => 'Parent Unit',
            'level'       => 'Level',
            'sort_order'  => 'Urutan',
            'is_active'   => 'Status Aktif',
            'description' => 'Deskripsi',
        ];
    }
}
