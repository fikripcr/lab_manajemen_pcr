<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class SuratBebasLabRequest extends BaseRequest
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
        if ($this->isMethod('POST')) {
            return [
                'catatan' => 'nullable|string|max:1000',
            ];
        }

        return [
            'status'  => 'required|in:approved,rejected,tangguhkan',
            'catatan' => 'nullable|string|max:1000',
        ];
    }
}
