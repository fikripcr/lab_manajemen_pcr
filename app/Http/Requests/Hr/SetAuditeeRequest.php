<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class SetAuditeeRequest extends BaseRequest
{
    /**
     */

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

    public function attributes(): array
    {
        return [
            'auditee_user_id' => 'Auditee',
        ];
    }
}
