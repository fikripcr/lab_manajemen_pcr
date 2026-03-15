<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class SearchRequest extends BaseRequest
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
            'q' => 'nullable|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'q' => 'Pencarian',
        ];
    }
}
