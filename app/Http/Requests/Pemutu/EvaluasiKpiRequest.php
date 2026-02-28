<?php
namespace App\Http\Requests\Pemutu;

use App\Http\Requests\BaseRequest;

class EvaluasiKpiRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'realization'      => 'nullable|string|max:255',
            'kpi_analisis'     => 'nullable|string',
            'attachment'       => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
            'kpi_links_name'   => 'nullable|array',
            'kpi_links_name.*' => 'nullable|string|max:255',
            'kpi_links_url.*'  => 'nullable|url|max:1000',
        ];
    }

    public function attributes(): array
    {
        return [
            'realization'      => 'Realisasi KPI',
            'kpi_analisis'     => 'Analisis KPI',
            'attachment'       => 'Lampiran',
            'kpi_links_name'   => 'Nama Link',
            'kpi_links_name.*' => 'Nama Link',
            'kpi_links_url'    => 'URL Link',
            'kpi_links_url.*'  => 'URL Link',
        ];
    }
}
