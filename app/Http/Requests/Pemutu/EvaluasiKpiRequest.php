<?php
namespace App\Http\Requests\Pemutu;

use Illuminate\Foundation\Http\FormRequest;

class EvaluasiKpiRequest extends FormRequest
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
            'kpi_links_url'    => 'nullable|array',
            'kpi_links_url.*'  => 'nullable|url|max:1000',
        ];
    }
}
