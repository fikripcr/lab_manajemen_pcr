<?php
namespace App\Http\Requests\Sys;

use App\Http\Requests\BaseRequest;

class UserImportRequest extends BaseRequest
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
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // Max 10MB
        ];
    }

    public function attributes(): array
    {
        return [
            'file' => 'File Import',
        ];
    }
}
