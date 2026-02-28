<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class DokSubRequest extends BaseRequest
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
    protected function prepareForValidation()
    {
        if ($this->has('dok_id')) {
            try {
                $this->merge([
                    'dok_id' => decryptId($this->dok_id),
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

        $kodeRule = Rule::unique('pemutu_dok_sub', 'kode')->whereNull('deleted_at')->whereNotNull('kode');
        if ($ignoreId) {
            $kodeRule = $kodeRule->ignore($ignoreId, 'doksub_id');
        }

        $rules = [
            'judul'                 => 'required|string|max:150',
            'kode'                  => ['nullable', 'string', 'max:50', $kodeRule],
            'isi'                   => 'nullable|string',
            'is_hasilkan_indikator' => 'nullable|boolean',
        ];

        if ($this->isMethod('post')) {
            $rules['dok_id'] = 'required|exists:pemutu_dokumen,dok_id';
        }

        return $rules;
    }
}
