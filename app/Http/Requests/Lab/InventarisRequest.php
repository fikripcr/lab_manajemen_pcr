<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class InventarisRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
            'lab_id'             => ['required', 'exists:lab_labs,lab_id'],
            'nama_alat'          => ['required', 'string', 'max:255'],
            'jenis_alat'         => ['required', 'string', 'max:255'],
            'kondisi_terakhir'   => ['required', 'string', 'max:255'],
            'tanggal_pengecekan' => ['required', 'date'],
        ];
    }
}
