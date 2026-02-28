<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class SoftwareRequest extends BaseRequest
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
        // Update rules
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return [
                'status'        => 'required|in:menunggu_approval,disetujui,ditolak',
                'catatan_admin' => 'nullable|string',
            ];
        }

        // Store rules
        return [
            'periodsoftreq_id'  => 'required|exists:lab_periode_softrequest,periodsoftreq_id',
            'mata_kuliah_ids'   => 'required|array',
            'mata_kuliah_ids.*' => 'exists:lab_mata_kuliahs,mata_kuliah_id',
            'nama_software'     => 'required|string|max:255',
            'versi'             => 'nullable|string|max:50',
            'url_download'      => 'nullable|url',
            'deskripsi'         => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'status'            => 'Status',
            'catatan_admin'     => 'Catatan Admin',
            'periodsoftreq_id'  => 'Periode',
            'mata_kuliah_ids'   => 'Mata Kuliah',
            'mata_kuliah_ids.*' => 'Mata Kuliah',
            'nama_software'     => 'Nama Software',
            'versi'             => 'Versi',
            'url_download'      => 'URL Download',
            'deskripsi'         => 'Deskripsi',
        ];
    }
}
