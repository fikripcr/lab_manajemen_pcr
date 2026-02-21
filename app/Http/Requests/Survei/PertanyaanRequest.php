<?php
namespace App\Http\Requests\Survei;

use Illuminate\Foundation\Http\FormRequest;

class PertanyaanRequest extends FormRequest
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
            'halaman_id'      => 'required|exists:survei_halaman,id',
            'tipe'            => 'required|in:Teks_Singkat,Esai,Angka,Pilihan_Ganda,Kotak_Centang,Dropdown,Skala_Linear,Tanggal,Upload_File,Rating_Bintang',
            'teks_pertanyaan' => 'required|string',
            'is_required'     => 'nullable|boolean',
            'urutan'          => 'nullable|integer',
        ];
    }
}
