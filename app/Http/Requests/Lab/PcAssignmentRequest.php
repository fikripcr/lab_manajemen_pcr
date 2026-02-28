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
            'nomor_loker' => 'nullable|integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id'     => 'User',
            'nomor_pc'    => 'Nomor PC',
            'nomor_loker' => 'Nomor Loker',
        ];
    }
}
