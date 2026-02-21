<?php
namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class PeriodeKpiRequest extends FormRequest
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
            'nama'            => 'required|string|max:100',
            'semester'        => 'required|in:Ganjil,Genap',
            'tahun_akademik'  => 'required|string|max:20',
            'tahun'           => 'required|integer',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ];
    }
}
