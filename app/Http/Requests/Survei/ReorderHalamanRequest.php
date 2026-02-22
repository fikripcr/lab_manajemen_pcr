<?php
namespace App\Http\Requests\Survei;

use Illuminate\Foundation\Http\FormRequest;

class ReorderHalamanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('order') && is_array($this->input('order'))) {
            $decryptedOrder = array_map(function ($id) {
                return decryptIdIfEncrypted($id);
            }, $this->input('order'));

            $this->merge([
                'order' => $decryptedOrder,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order'   => 'required|array',
            'order.*' => 'integer',
        ];
    }
}
