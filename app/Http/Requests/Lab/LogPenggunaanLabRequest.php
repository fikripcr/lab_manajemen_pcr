<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class LogPenggunaanLabRequest extends BaseRequest
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
            'nama_peserta' => 'required|string',
            'lab_id'       => 'required_without:kegiatan_id',
            'kegiatan_id'  => 'nullable',
            'nomor_pc'     => 'nullable|integer',
            'kondisi'      => 'required|string',
        ];
    }

    /**
     * Prepare the request for validation, decrypting encrypted IDs.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'lab_id'      => decryptIdIfEncrypted($this->lab_id),
            'kegiatan_id' => decryptIdIfEncrypted($this->kegiatan_id),
        ]);
    }
}
