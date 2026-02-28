<?php
namespace App\Http\Requests\Shared;

use App\Http\Requests\BaseRequest;

class SetAuditeeRequest extends BaseRequest
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
            'auditee_user_id' => 'required|exists:users,id',
        ];
    }
}
