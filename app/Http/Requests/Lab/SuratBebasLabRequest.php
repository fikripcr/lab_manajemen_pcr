<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class SuratBebasLabRequest extends BaseRequest
{
    /**
     */

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
            'catatan' => 'nullable|string|max:1000',
        ];
    }

    public function attributes(): array
    {
        return [
            'status'  => 'Status',
            'catatan' => 'Catatan',
        ];
    }
}
