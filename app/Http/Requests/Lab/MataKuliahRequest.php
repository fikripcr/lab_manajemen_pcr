<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class MataKuliahRequest extends BaseRequest
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
        $mataKuliahId = $this->route('mata_kuliah'); // Get the mata kuliah ID from the route for update operations

        return [
            'kode_mk' => $mataKuliahId ? 'required|string|max:20|unique:mata_kuliahs,kode_mk,' . $mataKuliahId : 'required|string|max:20|unique:mata_kuliahs,kode_mk',
            'sks'     => 'required|integer|min:1|max:6',
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_mk' => 'Kode Mata Kuliah',
            'nama_mk' => 'Nama Mata Kuliah',
            'sks'     => 'SKS',
        ];
    }
}
