<?php
namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class PerizinanRequest extends FormRequest
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
            'jenisizin_id'           => 'required|exists:hr_jenis_izin,jenisizin_id',
            'pengusul'               => 'required|exists:pegawai,pegawai_id',
            'pekerjaan_ditinggalkan' => 'nullable|string|max:500',
            'keterangan'             => 'nullable|string|max:1000',
            'alamat_izin'            => 'nullable|string',
            'tgl_awal'               => 'required|date',
            'tgl_akhir'              => 'required|date|after_or_equal:tgl_awal',
            'jam_awal'               => 'nullable',
            'jam_akhir'              => 'nullable',
            'status'                 => 'nullable|in:Draft,Pending,Approved,Rejected',
            'pejabat'                => 'nullable|string|max:191',
            'keterangan_approval'    => 'nullable|string',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'jenisizin_id.required'    => 'Jenis izin harus dipilih.',
            'jenisizin_id.exists'      => 'Jenis izin tidak ditemukan.',
            'pengusul.required'        => 'Pengusul harus dipilih.',
            'pengusul.exists'          => 'Pengusul tidak ditemukan.',
            'tgl_awal.required'        => 'Tanggal mulai harus diisi.',
            'tgl_awal.date'            => 'Tanggal mulai harus berupa tanggal.',
            'tgl_akhir.required'       => 'Tanggal selesai harus diisi.',
            'tgl_akhir.date'           => 'Tanggal selesai harus berupa tanggal.',
            'tgl_akhir.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ];
    }
}
