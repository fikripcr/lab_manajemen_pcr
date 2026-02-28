<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class RenopRequest extends BaseRequest
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
            'indikator' => 'required|string',
            'target'    => 'required|string',
            'parent_id' => 'nullable|exists:pemutu_indikator,indikator_id',
            'seq'       => 'nullable|integer',
            'type'      => 'required|in:renop',
        ];
    }
}
