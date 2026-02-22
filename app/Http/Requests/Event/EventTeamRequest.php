<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class EventTeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pegawai_id' => 'required|exists:hr_pegawai,pegawai_id',
            'role' => 'nullable|string|max:100',
            'jabatan_dalam_tim' => 'nullable|string|max:100',
            'is_pic' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'pegawai_id.required' => 'Pegawai wajib dipilih',
            'pegawai_id.exists' => 'Pegawai tidak valid',
            'role.max' => 'Peran maksimal 100 karakter',
            'jabatan_dalam_tim.max' => 'Jabatan maksimal 100 karakter',
        ];
    }

    /**
     * Get the validation attributes for the request.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'pegawai_id' => 'pegawai',
            'role' => 'peran dalam tim',
            'jabatan_dalam_tim' => 'jabatan dalam kegiatan',
            'is_pic' => 'status PIC',
        ];
    }
}
