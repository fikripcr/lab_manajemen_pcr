<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class IndikatorRequest extends BaseRequest
{
    /**
     */

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'type'            => 'required|in:renop,standar,performa',
            'doksub_ids'      => 'nullable|array',
            'parent_id'       => 'nullable|string',
            'renstra_poin_id' => 'nullable|string',
            'no_indikator'    => 'nullable|string|max:50',
            'indikator'       => 'required|string',
            'target'          => 'nullable|string',
            'jenis_data'      => 'nullable|in:Kualitatif,Kuantitatif',
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
            'renstra_poin_id' => 'Poin Renstra',
            'no_indikator'    => 'Nomor Indikator',
            'indikator'       => 'Nama Indikator',
            'target'          => 'Target',
            'jenis_data'      => 'Jenis Data',
            'seq'             => 'Urutan',
            'labels'          => 'Label',
            'org_units'       => 'Unit Organisasi',
            'kpi_assignments' => 'Penugasan KPI',
            'skala'           => 'Skala',
            'skala.*'         => 'Skala',
            'keterangan'      => 'Keterangan',
        ];
    }
}
