<?php

namespace App\Exports;

use App\Models\Pemutu\IndikatorSummary;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Number;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IndikatorSummaryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $query = IndikatorSummary::query();

        // Apply filters
        if (!empty($this->filters['type']) && in_array($this->filters['type'], ['standar', 'performa'])) {
            $query->where('type', $this->filters['type']);
        }

        if (!empty($this->filters['kelompok_indikator'])) {
            $query->where('kelompok_indikator', $this->filters['kelompok_indikator']);
        }

        if (!empty($this->filters['year'])) {
            $query->whereYear('periode_mulai', $this->filters['year']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('no_indikator', 'LIKE', "%{$search}%")
                    ->orWhere('indikator', 'LIKE', "%{$search}%")
                    ->orWhere('parent_no_indikator', 'LIKE', "%{$search}%")
                    ->orWhere('all_labels', 'LIKE', "%{$search}%")
                    ->orWhere('all_org_units', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('seq')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $isPerforma = !empty($this->filters['type']) && $this->filters['type'] === 'performa';

        if ($isPerforma) {
            return [
                'No',
                'No. Indikator',
                'Indikator',
                'Parent',
                'Kelompok',
                'Labels',
                'Total Unit',
                'KPI Pegawai',
                'KPI Draft',
                'KPI Submitted',
                'KPI Approved',
                'KPI Rejected',
                'KPI Avg Score',
                'KPI Min Score',
                'KPI Max Score',
            ];
        }

        return [
            'No',
            'No. Indikator',
            'Indikator',
            'Parent',
            'Kelompok',
            'Labels',
            'Total Unit',
            'Isi Evaluasi Diri',
            'ED Progress (%)',
            'ED Avg Skala',
            'Pelaksanaan AMI',
            'AMI KTS',
            'AMI Terpenuhi',
            'AMI Terlampaui',
            'Pengendalian Filled',
            'Pengendalian Progress (%)',
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        $isPerforma = !empty($this->filters['type']) && $this->filters['type'] === 'performa';

        if ($isPerforma) {
            return [
                $row->seq,
                $row->no_indikator ?? '-',
                $row->indikator ?? '-',
                $row->parent_no_indikator ?? '-',
                $row->kelompok_indikator ?? '-',
                $row->all_labels ?? '-',
                $row->total_org_units ?? 0,
                $row->total_pegawai_with_kpi ?? 0,
                $row->kpi_draft_count ?? 0,
                $row->kpi_submitted_count ?? 0,
                $row->kpi_approved_count ?? 0,
                $row->kpi_rejected_count ?? 0,
                $row->kpi_avg_score ? round($row->kpi_avg_score, 1) : 0,
                $row->kpi_min_score ? round($row->kpi_min_score, 1) : 0,
                $row->kpi_max_score ? round($row->kpi_max_score, 1) : 0,
            ];
        }

        $totalUnits = $row->ed_total_units ?? 0;
        $edFilled = $row->ed_filled_units ?? 0;
        $edProgress = $totalUnits > 0 ? round(($edFilled / $totalUnits) * 100, 1) : 0;
        $edAvgSkala = $row->ed_skala_avg ? round($row->ed_skala_avg, 1) : 0;

        $pengendFilled = $row->pengend_filled_units ?? 0;
        $pengendProgress = $totalUnits > 0 ? round(($pengendFilled / $totalUnits) * 100, 1) : 0;

        return [
            $row->seq,
            $row->no_indikator ?? '-',
            $row->indikator ?? '-',
            $row->parent_no_indikator ?? '-',
            $row->kelompok_indikator ?? '-',
            $row->all_labels ?? '-',
            $totalUnits,
            $edFilled,
            $edProgress,
            $edAvgSkala,
            $row->ami_assessed_units ?? 0,
            $row->ami_kts_units ?? 0,
            $row->ami_terpenuhi_units ?? 0,
            $row->ami_terlampaui_units ?? 0,
            $pengendFilled,
            $pengendProgress,
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
