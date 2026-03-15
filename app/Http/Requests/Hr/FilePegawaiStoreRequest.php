<?php
namespace App\Http\Requests\Hr;

use App\Http\Requests\BaseRequest;

class FilePegawaiStoreRequest extends BaseRequest
{
    /**
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pegawai_id'   => 'required',
            'jenisfile_id' => 'required|exists:hr_jenis_file,jenisfile_id',
            'file'         => 'required|file|max:10240', // Max 10MB
            'keterangan'   => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'pegawai_id'   => 'Pegawai',
            'jenisfile_id' => 'Jenis File',
            'file'         => 'File Pegawai',
            'keterangan'   => 'Keterangan',
        ];
    }
}
