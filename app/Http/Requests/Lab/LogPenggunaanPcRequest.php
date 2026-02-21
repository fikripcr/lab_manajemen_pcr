<?php
namespace App\Http\Requests\Lab;

use Illuminate\Foundation\Http\FormRequest;

class LogPenggunaanPcRequest extends FormRequest
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
            'status_pc'    => 'required|in:Baik,Rusak',
            'catatan_umum' => 'nullable|string',
            'jadwal_id'    => 'required',
            'lab_id'       => 'required',
        ];
    }
}
