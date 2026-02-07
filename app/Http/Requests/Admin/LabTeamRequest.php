<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LabTeamRequest extends FormRequest
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
            'user_id'       => 'required', // ID is encrypted string
            'jabatan'       => 'nullable|string|max:255',
            'tanggal_mulai' => 'nullable|date',
        ];
    }
}
