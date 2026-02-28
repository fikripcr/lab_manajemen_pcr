<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class IndisiplinerRequest extends BaseRequest
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
            'jenisindisipliner_id' => 'required|exists:hr_jenis_indisipliner,jenisindisipliner_id',
            'tgl_indisipliner'     => 'required|date',
            'pegawai_id'           => 'required|array|min:1',
            'pegawai_id.*'         => 'exists:pegawai,pegawai_id',
            'keterangan'           => 'nullable|string|max:1000',
            'bukti'                => 'nullable|file|mimes:pdf,docx,doc,jpg,jpeg,png|max:5120',
        ];
    }

    public function attributes(): array
    {
        return [
            'jenisindisipliner_id' => 'Jenis Indisipliner',
            'tgl_indisipliner'     => 'Tanggal Indisipliner',
            'pegawai_id'           => 'Pegawai',
            'pegawai_id.*'         => 'Pegawai',
            'keterangan'           => 'Keterangan',
            'bukti'                => 'Bukti File',
        ];
    }
}
