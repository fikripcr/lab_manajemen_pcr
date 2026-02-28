<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class JenisIndisiplinerRequest extends BaseRequest
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
        $uniqueRule = 'unique:hr_jenis_indisipliner,jenis_indisipliner';

        // On update, exclude current record from unique check
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $jenisIndisipliner  = $this->route('jenis_indisipliner');
            $uniqueRule        .= ',' . $jenisIndisipliner->jenisindisipliner_id . ',jenisindisipliner_id';
        }

        return [
            'jenis_indisipliner' => 'required|string|max:100|' . $uniqueRule,
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
            'jenis_indisipliner.required' => 'Jenis indisipliner harus diisi.',
            'jenis_indisipliner.string'   => 'Jenis indisipliner harus berupa string.',
            'jenis_indisipliner.max'      => 'Jenis indisipliner maksimal 100 karakter.',
            'jenis_indisipliner.unique'   => 'Jenis indisipliner sudah ada.',
            'nama_jenis.required'         => 'Nama jenis indisipliner wajib diisi.',
            'poin.required'               => 'Poin indisipliner wajib diisi.',
        ]);
    }
}
