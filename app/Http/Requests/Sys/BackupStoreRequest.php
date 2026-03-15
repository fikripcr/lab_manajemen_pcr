<?php
namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class BackupStoreRequest extends BaseRequest
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
            'type' => 'required|string|in:files,database,full',
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => 'Tipe Backup',
        ];
    }
}
