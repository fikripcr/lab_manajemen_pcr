<?php
namespace App\Http\Requests\Pmb;

use App\Http\Requests\BaseRequest;

class VerifyDocRequest extends BaseRequest
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
            'status'     => 'required|in:Valid,Tidak_Valid',
            'keterangan' => 'nullable|string|max:1000',
        ];
    }

    public function attributes(): array
    {
        return [
            'status'     => 'Status Dokumen',
            'keterangan' => 'Keterangan',
        ];
    }
}
