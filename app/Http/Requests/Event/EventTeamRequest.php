<?php

namespace App\Http\Requests\Event;

use App\Http\Requests\BaseRequest;

class EventTeamRequest extends BaseRequest
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

    public function attributes(): array
    {
        return [
            'pegawai_id'        => 'Pegawai',
            'role'              => 'Peran Dalam Tim',
            'jabatan_dalam_tim' => 'Jabatan Dalam Kegiatan',
            'is_pic'            => 'Status PIC',
        ];
    }
}
