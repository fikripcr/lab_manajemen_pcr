<?php
namespace App\Http\Requests\Lab;

use Illuminate\Foundation\Http\FormRequest;

class LogPenggunaanLabRequest extends FormRequest
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
}
