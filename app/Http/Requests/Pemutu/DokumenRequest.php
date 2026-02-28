<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class DokumenRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->has('parent_id') && $this->parent_id) {
            try {
                $this->merge([
                    'parent_id' => decryptId($this->parent_id),
                ]);
            } catch (\Exception $e) {
                // Keep original value if decryption fails, validation will likely fail
            }
        }

        if ($this->has('parent_doksub_id') && $this->parent_doksub_id) {
            try {
                $this->merge([
                    'parent_doksub_id' => decryptId($this->parent_doksub_id),
                ]);
            } catch (\Exception $e) {
                // Keep original value
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Get current record ID for ignore-self on update
        $ignoreId = $this->isMethod('put') ? decryptIdIfEncrypted($this->route('id')) : null;

        $kodeRule = Rule::unique('pemutu_dokumen', 'kode')->whereNull('deleted_at')->whereNotNull('kode');
        if ($ignoreId) {
            $kodeRule = $kodeRule->ignore($ignoreId, 'dok_id');
        }

        return [
            'judul'            => 'required|string|max:255',
            'parent_id'        => 'nullable|exists:pemutu_dokumen,dok_id',
            'parent_doksub_id' => 'nullable|integer|exists:pemutu_dok_sub,doksub_id',
            'kode'             => ['nullable', 'string', 'max:50', $kodeRule],
            'isi'              => 'nullable|string',
            'jenis'            => 'required|in:visi,misi,rjp,renstra,renop,standar,formulir,manual_prosedur,dll',
            'periode'          => 'nullable|integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'judul'            => 'Judul Dokumen',
            'parent_id'        => 'Dokumen Induk',
            'parent_doksub_id' => 'Sub-Dokumen Induk',
            'kode'             => 'Kode Dokumen',
            'isi'              => 'Isi Dokumen',
            'jenis'            => 'Jenis Dokumen',
            'periode'          => 'Periode',
        ];
    }
}
