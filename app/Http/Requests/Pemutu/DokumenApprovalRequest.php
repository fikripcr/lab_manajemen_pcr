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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'personil_id' => 'required|exists:personil,personil_id',
            'status'      => 'required|in:terima,tolak,tangguhkan',
            'komentar'    => 'nullable|string',
        ];
    }
}
