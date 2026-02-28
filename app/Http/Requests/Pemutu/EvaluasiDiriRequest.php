<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class EvaluasiDiriRequest extends BaseRequest
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
            'ed_capaian'      => 'required|string',
            'ed_analisis'     => 'required|string',
            'ed_attachment'   => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx|max:5120',
            'target_unit_id'  => 'nullable|integer',
            'ed_links_name'   => 'nullable|array',
            'ed_links_name.*' => 'nullable|string',
            'ed_links_url'    => 'nullable|array',
            'ed_links_url.*'  => 'nullable|url',
        ];
    }
}
