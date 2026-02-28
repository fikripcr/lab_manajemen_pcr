<?php

namespace App\Http\Requests\Eoffice;

use App\Models\Eoffice\JenisLayanan;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Validator;

class LayananStoreRequest extends BaseRequest
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
        return [
            'jenislayanan_id' => 'required|exists:eoffice_jenis_layanan,jenislayanan_id',
            'keterangan'      => 'nullable|string|max:1000',
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
            'jenislayanan_id.required' => 'Jenis layanan harus dipilih.',
            'jenislayanan_id.exists'   => 'Jenis layanan tidak ditemukan.',
            'keterangan.max'           => 'Keterangan maksimal 1000 karakter.',
        ];
    }

    /**
     * Configure the validator instance (for dynamic rules)
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Get the jenis layanan to validate dynamic fields
            $jenisLayananId = $this->input('jenislayanan_id');
            
            if (!$jenisLayananId) {
                return;
            }

            $jenisLayanan = JenisLayanan::with('isians.kategoriIsian')
                ->find($jenisLayananId);

            if (!$jenisLayanan) {
                return;
            }

            // Validate each required field
            foreach ($jenisLayanan->isians as $isian) {
                if (!$isian->is_required) {
                    continue;
                }

                $kategoriIsian = $isian->kategoriIsian;
                if (!$kategoriIsian) {
                    continue;
                }

                $fieldName = 'field_' . $kategoriIsian->kategoriisian_id;
                $value     = $this->input($fieldName);

                // Check if required field is empty
                if (empty($value) && !$this->hasFile($fieldName)) {
                    $validator->errors()->add(
                        $fieldName,
                        "Field '{$kategoriIsian->nama_isian}' wajib diisi."
                    );
                    continue;
                }

                // Validate file uploads
                if ($kategoriIsian->type === 'file' && $this->hasFile($fieldName)) {
                    $file = $this->file($fieldName);
                    
                    // Validate file size (max 2MB)
                    if ($file->getSize() > 2 * 1024 * 1024) {
                        $validator->errors()->add(
                            $fieldName,
                            "File '{$kategoriIsian->nama_isian}' terlalu besar (maksimal 2MB)."
                        );
                    }

                    // Validate file type
                    $allowedMimes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
                    $extension = strtolower($file->getClientOriginalExtension());
                    
                    if (!in_array($extension, $allowedMimes)) {
                        $validator->errors()->add(
                            $fieldName,
                            "Format file '{$kategoriIsian->nama_isian}' tidak diizinkan. Format yang diperbolehkan: " . implode(', ', $allowedMimes)
                        );
                    }
                }

                // Validate email fields
                if ($kategoriIsian->type === 'email' && !empty($value)) {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $validator->errors()->add(
                            $fieldName,
                            "Format email '{$kategoriIsian->nama_isian}' tidak valid."
                        );
                    }
                }

                // Validate number fields
                if ($kategoriIsian->type === 'number' && !empty($value)) {
                    if (!is_numeric($value)) {
                        $validator->errors()->add(
                            $fieldName,
                            "Field '{$kategoriIsian->nama_isian}' harus berupa angka."
                        );
                    }
                }
            }
        });
    }
}
