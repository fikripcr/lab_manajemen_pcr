<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class PcAssignmentRequest extends BaseRequest
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
            'user_id'     => 'required|exists:users,id',
            'nomor_pc'    => 'required|integer|min:1',
            'nomor_loker' => 'nullable|integer',
        ];
    }
}
