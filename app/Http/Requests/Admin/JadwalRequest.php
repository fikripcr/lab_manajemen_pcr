<?php

namespace App\Http\Requests\Admin;

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
            'semester_id' => 'required|exists:semesters,semester_id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:users,id',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'lab_id' => 'required|exists:labs,lab_id',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'semester_id.required' => 'Semester wajib diisi.',
            'semester_id.exists' => 'Semester yang dipilih tidak valid.',
            'mata_kuliah_id.required' => 'Mata kuliah wajib diisi.',
            'mata_kuliah_id.exists' => 'Mata kuliah yang dipilih tidak valid.',
            'dosen_id.required' => 'Dosen wajib diisi.',
            'dosen_id.exists' => 'Dosen yang dipilih tidak valid.',
            'hari.required' => 'Hari wajib diisi.',
            'hari.string' => 'Hari harus berupa teks.',
            'hari.max' => 'Hari maksimal :max karakter.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_mulai.date_format' => 'Format jam mulai harus HH:MM.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.date_format' => 'Format jam selesai harus HH:MM.',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'lab_id.required' => 'Lab wajib diisi.',
            'lab_id.exists' => 'Lab yang dipilih tidak valid.',
        ];
    }
}