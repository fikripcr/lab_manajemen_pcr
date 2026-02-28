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

    public function attributes(): array
    {
        return [
            'file' => 'File Excel',
        ];
    }
}
