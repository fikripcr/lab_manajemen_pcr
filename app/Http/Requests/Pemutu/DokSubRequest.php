<?php
namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class DokSubRequest extends FormRequest
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
        $rules = [
            'judul'                 => 'required|string|max:150',
            'isi'                   => 'nullable|string',
            'seq'                   => 'nullable|integer',
            'is_hasilkan_indikator' => 'nullable|boolean',
        ];

        if ($this->isMethod('post')) {
            $rules['dok_id'] = 'required|exists:pemutu_dokumen,dok_id';
        }

        return $rules;
    }
}
