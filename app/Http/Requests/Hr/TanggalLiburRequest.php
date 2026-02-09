<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class TanggalLiburRequest extends FormRequest
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
            'entries'              => 'required|array',
            'entries.*.dates'      => 'required', // String with multiple dates
            'entries.*.keterangan' => 'required|string',
            'tahun'                => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'entries.required' => 'Data entry tidak boleh kosong.',
            'tahun.required'   => 'Tahun harus dipilih.',
        ];
    }
}
