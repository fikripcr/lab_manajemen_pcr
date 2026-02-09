<?php
namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class DokumenRequest extends FormRequest
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
            'judul'     => 'required|string|max:255',
            'parent_id' => 'nullable|exists:dokumen,dok_id',
            'kode'      => 'nullable|string|max:50',
            'isi'       => 'nullable|string',
            'jenis'     => 'required|in:visi,misi,rjp,renstra,renop,standar,formulir,sop,dll',
            'periode'   => 'nullable|integer',
        ];
    }
}
