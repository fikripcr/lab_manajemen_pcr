<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class LabelTypeRequest extends BaseRequest
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
     */
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:100',
            'color'       => 'nullable|string|max:20',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'Nama Tipe Label',
            'description' => 'Deskripsi',
            'color'       => 'Warna',
        ];
    }
}
