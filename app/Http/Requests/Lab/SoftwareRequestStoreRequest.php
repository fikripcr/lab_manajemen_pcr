<?php
namespace App\Http\Requests\Lab;

use App\Http\Requests\BaseRequest;

class SoftwareRequestStoreRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'periodsoftreq_id'  => 'required|exists:lab_periode_softrequest,periodsoftreq_id',
            'mata_kuliah_ids'   => 'required|array',
            'mata_kuliah_ids.*' => 'exists:lab_mata_kuliahs,mata_kuliah_id',
            'nama_software'     => 'required|string|max:255',
            'versi'             => 'nullable|string|max:50',
            'url_download'      => 'nullable|url',
            'deskripsi'         => 'required|string',
            // Dosen ID diambil dari Auth::user()->id di service
        ];
    }
}
