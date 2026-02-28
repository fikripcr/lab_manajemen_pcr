<?php

namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class LabTeamStoreRequest extends BaseRequest
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
            'user_id' => 'required',
            'role' => 'required|in:pic,member',
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

    public function attributes(): array
    {
        return [
            'user_id' => 'Anggota Tim',
            'role'    => 'Role',
            'jabatan' => 'Jabatan',
        ];
    }
}
