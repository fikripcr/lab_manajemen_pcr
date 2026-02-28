<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class IndikatorRequest extends BaseRequest
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
            'parent_id'       => 'nullable|string',
            'no_indikator'    => 'nullable|string|max:50',
            'indikator'       => 'required|string',
            'target'          => 'nullable|string',
            'jenis_indikator' => 'nullable|string|max:30',
            'jenis_data'      => 'nullable|string|max:30',
            'periode_jenis'   => 'nullable|string|max:30',
            'periode_mulai'   => 'nullable|date',
            'periode_selesai' => 'nullable|date',
            'seq'             => 'nullable|integer',
            'level_risk'      => 'nullable|string|max:20',
            'origin_from'     => 'nullable|string|max:30',
            'labels'          => 'nullable|array',
            'org_units'       => 'nullable|array',
            'kpi_assignments' => 'nullable|array',
            'skala'           => 'nullable|array',
            'skala.*'         => 'nullable|string',
            'keterangan'      => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'type'            => 'Tipe Indikator',
            'doksub_ids'      => 'Sub-Dokumen',
            'parent_id'       => 'Indikator Induk',
            'no_indikator'    => 'Nomor Indikator',
            'indikator'       => 'Nama Indikator',
            'target'          => 'Target',
            'jenis_indikator' => 'Jenis Indikator',
            'jenis_data'      => 'Jenis Data',
            'periode_jenis'   => 'Jenis Periode',
            'periode_mulai'   => 'Periode Mulai',
            'periode_selesai' => 'Periode Selesai',
            'seq'             => 'Urutan',
            'level_risk'      => 'Level Risiko',
            'origin_from'     => 'Asal Indikator',
            'labels'          => 'Label',
            'org_units'       => 'Unit Organisasi',
            'kpi_assignments' => 'Penugasan KPI',
            'skala'           => 'Skala',
            'skala.*'         => 'Skala',
            'keterangan'      => 'Keterangan',
        ];
    }
}
