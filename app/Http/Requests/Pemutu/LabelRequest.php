<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class LabelRequest extends BaseRequest
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
     */
    public function rules(): array
    {
        return [
            'type_id'     => 'required|exists:pemutu_label_types,labeltype_id',
            'name'        => 'required|string|max:100',
            'slug'        => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ];
    }
}
