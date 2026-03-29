<?php

namespace App\Exports\Pemutu;

use App\Services\Pemutu\IndikatorSummaryPerformaService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IndikatorSummaryPerformaExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $service = new IndikatorSummaryPerformaService;

        return $service->getQuery($this->request)
            ->with(['indikator.parent', 'indikator.labels.label'])
            ->get()
            ->sortBy(function ($item) {
                return $item->indikator->no_indikator ?? '';
            });
    }

    public function headings(): array
    {
        return [
            'No Indikator',
            'No Indikator Parent',
            'Kelompok Indikator',
            'Pernyataan Indikator',
            'Tahun Periode',
            'Nama Pegawai',
            'NIP Pegawai',
            'Nama Unit Kerja',
            'Target',
            'Capaian',
            'Analisis',
            'Bobot',
            'Skor Akhir',
            'Status Evaluasi',
        ];
    }

    public function map($row): array
    {
        $pegawaiName = $row->pegawai?->nama ?? '-';
        $pegawaiNip = $row->pegawai?->nip ?? '-';
        $unitName = $row->pegawai?->orgUnit?->name ?? '-';

        return [
            $row->indikator?->no_indikator ?? '-',
            $row->indikator?->parent?->no_indikator ?? '-',
            $row->indikator?->kelompok_indikator ?? '-',
            $row->indikator?->indikator ?? '-',
            date('Y', strtotime($row->indikator?->periode_mulai ?? now())),
            $pegawaiName,
            $pegawaiNip,
            $unitName,
            $row->target_value ?? '-',
            $row->realization ?? '-',
            strip_tags($row->kpi_analisis ?? '-'),
            $row->weight !== null ? $row->weight.'%' : '-',
            $row->score !== null ? number_format($row->score, 2) : '-',
            ucfirst($row->status ?? 'Draft'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
