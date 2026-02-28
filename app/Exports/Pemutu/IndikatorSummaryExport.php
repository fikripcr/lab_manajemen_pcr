<?php

namespace App\Exports\Pemutu;

use App\Models\Pemutu\IndikatorSummaryStandar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IndikatorSummaryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = IndikatorSummaryStandar::query();

        // Filter by kelompok indikator
        if (!empty($this->filters['kelompok_indikator'])) {
            $query->where('kelompok_indikator', $this->filters['kelompok_indikator']);
        }

        // Filter by year
        if (!empty($this->filters['year'])) {
            $query->whereYear('periode_mulai', $this->filters['year']);
        }

        // Search
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('no_indikator', 'LIKE', "%{$search}%")
                  ->orWhere('indikator', 'LIKE', "%{$search}%")
                  ->orWhere('parent_no_indikator', 'LIKE', "%{$search}%")
                  ->orWhere('label_details', 'LIKE', "%{$search}%")
                  ->orWhere('all_unit_names', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('no_indikator', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'No Indikator',
            'No Indikator Parent',
            'Pernyataan Indikator',
            'Kode Unit',
            'Nama Unit',
            'Label & Kelompok',
            'Periode',
            'Capaian ED',
            'Skala ED',
            'Analisis ED',
            'Status AMI',
            'Temuan AMI',
            'Sebab KTS',
            'Akibat KTS',
            'Rekomendasi KTS',
            'Status Pengendalian',
            'Target/Faktor',
            'Analisis Pengendalian',
            'Tindakan Penyesuaian',
        ];
    }

    public function map($row): array
    {
        return [
            $row->no_indikator,
            $row->parent_no_indikator,
            $row->indikator,
            $row->unit_code,
            $row->unit_name,
            $row->label_details . ' (' . $row->kelompok_indikator . ')',
            $row->periode_aktif,
            $row->ed_capaian,
            $row->ed_skala,
            strip_tags($row->ed_analisis),
            $row->ami_hasil_label,
            strip_tags($row->ami_hasil_temuan),
            strip_tags($row->ami_hasil_temuan_sebab),
            strip_tags($row->ami_hasil_temuan_akibat),
            strip_tags($row->ami_hasil_temuan_rekom),
            $row->pengend_status,
            strip_tags($row->pengend_target),
            strip_tags($row->pengend_analisis),
            strip_tags($row->pengend_penyesuaian),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
