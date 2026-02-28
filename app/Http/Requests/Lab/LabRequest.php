<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class LabRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
            'name'        => ['required', 'string', 'max:255'],
            'location'    => ['required', 'string', 'max:255'],
            'capacity'    => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ];
    }
}
