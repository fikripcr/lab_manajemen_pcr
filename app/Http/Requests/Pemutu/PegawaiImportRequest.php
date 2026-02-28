<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class PegawaiImportRequest extends BaseRequest
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
            'file' => 'required|mimes:xlsx,xls,csv',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'file.required' => 'File Excel wajib diunggah.',
            'file.mimes'    => 'File harus berformat .xlsx atau .xls.',
        ]);
    }
}
