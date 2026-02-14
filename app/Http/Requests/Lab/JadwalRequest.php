<?php
namespace App\Http\Requests\Lab;

use Illuminate\Foundation\Http\FormRequest;

class JadwalRequest extends FormRequest
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
        $jadwalId = $this->route('jadwal'); // Get the jadwal ID from the route for update operations

        return [
            'semester_id'    => 'required|exists:lab_semesters,semester_id',
            'mata_kuliah_id' => 'required|exists:lab_mata_kuliahs,mata_kuliah_id',
            'dosen_id'       => 'required|exists:users,id',
            'hari'           => 'required|string|max:20',
            'jam_mulai'      => 'required|date_format:H:i',
            'jam_selesai'    => 'required|date_format:H:i|after:jam_mulai',
            'lab_id'         => 'required|exists:lab_labs,lab_id',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return validation_messages_id();
    }
}
