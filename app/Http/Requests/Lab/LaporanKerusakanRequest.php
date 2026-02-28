<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class LaporanKerusakanRequest extends BaseRequest
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
            'lab_id'              => 'required',
            'inventaris_id'       => 'required',
            'deskripsi_kerusakan' => 'required|string',
            'bukti_foto'          => 'nullable|image|max:2048',
        ];
    }

    /**
     * Prepare the request for validation, decrypting encrypted IDs.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('inventaris_id')) {
            $this->merge([
                'inventaris_id' => decryptIdIfEncrypted($this->inventaris_id),
            ]);
        }
    }

    public function attributes(): array
    {
        return [
            'lab_id'              => 'Lab',
            'inventaris_id'       => 'Inventaris',
            'deskripsi_kerusakan' => 'Deskripsi Kerusakan',
            'bukti_foto'          => 'Bukti Foto',
        ];
    }
}
