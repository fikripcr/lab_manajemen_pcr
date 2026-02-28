<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class PresensiUpdateSettingsRequest extends BaseRequest
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
            'office_latitude'  => 'required|numeric',
            'office_longitude' => 'required|numeric',
            'office_address'   => 'required|string|max:500',
            'is_active'        => 'nullable', // handled as boolean in controller
        ];
    }

    public function attributes(): array
    {
        return [
            'office_latitude'  => 'Latitude Kantor',
            'office_longitude' => 'Longitude Kantor',
            'office_address'   => 'Alamat Kantor',
            'allowed_radius'   => 'Radius Diizinkan',
            'is_active'        => 'Status Aktif',
        ];
    }
}
