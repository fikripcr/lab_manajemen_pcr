<?php
namespace App\Http\Requests\Shared;

use App\Http\Requests\BaseRequest;

class FAQRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question' => 'required|string|max:191',
            'answer'   => 'required|string',
            'category' => 'nullable|string|max:191',
            'seq'      => 'nullable|integer',
        ];
    }
}
