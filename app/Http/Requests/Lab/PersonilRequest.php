<?php
namespace App\Http\Requests\Lab;

use Illuminate\Foundation\Http\FormRequest;

class PersonilRequest extends FormRequest
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
        $personilId = $this->route('personil') ? $this->route('personil')->personil_id : null;

        return [
            'nip'     => 'required|string|max:50|unique:personil,nip' . ($personilId ? ',' . $personilId . ',personil_id' : ''),
            'nama'    => 'required|string|max:255',
            'email'   => 'required|email|max:255|unique:personil,email' . ($personilId ? ',' . $personilId . ',personil_id' : ''),
            'posisi'  => 'required|string|max:255',
            'user_id' => 'nullable|string', // Encrypted ID
        ];
    }

    /**
     * Prepare the request for validation, decrypting encrypted IDs.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('user_id')) {
            $this->merge([
                'user_id' => decryptIdIfEncrypted($this->user_id),
            ]);
        }
    }
}
