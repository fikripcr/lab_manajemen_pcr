<?php
namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class IndikatorRequest extends FormRequest
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
        return [
            'type'            => 'required|in:renop,standar,performa',
            'doksub_ids'      => 'nullable|array',
            'doksub_ids.*'    => 'exists:pemutu_dok_sub,doksub_id',
            'parent_id'       => 'nullable|exists:pemutu_indikator,indikator_id',
            'no_indikator'    => 'nullable|string|max:50',
            'indikator'       => 'required|string',
            'target'          => 'nullable|string',
            'jenis_indikator' => 'nullable|string|max:30',
            'jenis_data'      => 'nullable|string|max:30',
            'periode_jenis'   => 'nullable|string|max:30',
            'periode_mulai'   => 'nullable|date',
            'periode_selesai' => 'nullable|date',
            'keterangan'      => 'nullable|string',
            'seq'             => 'nullable|integer',
            'level_risk'      => 'nullable|string|max:20',
            'origin_from'     => 'nullable|string|max:30',
            'labels'          => 'nullable|array',
            'org_units'       => 'nullable|array',
            'kpi_assignments' => 'nullable|array',
        ];
    }
}
