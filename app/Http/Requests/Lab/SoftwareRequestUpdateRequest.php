<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class SoftwareRequestUpdateRequest extends BaseRequest
{
    /**
     */

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'status'        => 'required|in:menunggu_approval,disetujui,ditolak',
            'catatan_admin' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'status'        => 'Status',
            'catatan_admin' => 'Catatan Admin',
        ];
    }
}
