<?php
namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class MyKpiRequest extends FormRequest
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
            'realization' => 'nullable|string',
            'score'       => 'nullable|numeric|min:0|max:100',
            'attachment'  => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:2048',
        ];
    }
}
