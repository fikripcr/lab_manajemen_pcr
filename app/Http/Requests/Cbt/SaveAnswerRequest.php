<?php
namespace App\Http\Requests\Cbt;

use Illuminate\Foundation\Http\FormRequest;

class SaveAnswerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'soal_id' => decryptIdIfEncrypted($this->soal_id),
            'opsi_id' => $this->opsi_id ? decryptIdIfEncrypted($this->opsi_id) : null,
        ]);
    }

    public function rules()
    {
        return [
            'soal_id'      => 'required|exists:cbt_soal,soal_id',
            'opsi_id'      => 'nullable|exists:cbt_opsi_jawaban,opsi_jawaban_id',
            'jawaban_esai' => 'nullable|string',
            'is_ragu'      => 'nullable|boolean',
        ];
    }
}
