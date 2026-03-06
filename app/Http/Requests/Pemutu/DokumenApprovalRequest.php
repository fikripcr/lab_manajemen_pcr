<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class DokumenApprovalRequest extends BaseRequest
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
        if ($this->has('personil_id') && $this->personil_id) {
            try {
                $this->merge([
                    'personil_id' => decryptId($this->personil_id),
                ]);
            } catch (\Exception $e) {
                // Keep original value if decryption fails, validation will likely fail
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'personil_id' => 'required|exists:personil,personil_id',
            'komentar'    => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'personil_id' => 'Personil Approval',
            'status'      => 'Status Approval',
            'komentar'    => 'Komentar',
        ];
    }
}
