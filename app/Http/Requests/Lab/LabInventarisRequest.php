<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class LabInventarisRequest extends BaseRequest
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
        $rules = [
            'no_series'  => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ];

        // inventaris_id only required when creating
        if ($this->isMethod('POST')) {
            $rules['inventaris_id'] = 'required|exists:lab_inventaris,inventaris_id';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'no_series'     => 'Nomor Seri',
            'keterangan'    => 'Keterangan',
            'inventaris_id' => 'Inventaris',
            'barcode'       => 'Barcode',
            'name'          => 'Nama Barang',
            'lab_id'        => 'Lab',
            'status'        => 'Status',
        ];
    }
}
