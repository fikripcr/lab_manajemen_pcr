<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class SoftwareRequestUpdateRequest extends BaseRequest
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
            'status'        => 'required|in:menunggu_approval,disetujui,ditolak',
            'catatan_admin' => 'nullable|string',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'software_name.required' => 'Nama software harus diisi.',
            'version.required'       => 'Versi software harus diisi.',
            'semester_id.required'   => 'Semester harus dipilih.',
            'category.required'      => 'Kategori software harus diisi.',
        ]);
    }
}
