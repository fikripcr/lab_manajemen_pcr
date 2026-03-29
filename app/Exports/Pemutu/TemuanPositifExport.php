<?php

namespace App\Exports\Pemutu;

use App\Models\Pemutu\PeriodeSpmi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemuanPositifExport implements FromCollection, ShouldAutoSize, WithColumnWidths, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $indicators;

    protected $periode;

    public function __construct($indicators, PeriodeSpmi $periode)
    {
        $this->indicators = $indicators;
        $this->periode = $periode;
    }

    public function collection()
    {
        return $this->indicators;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Indikator',
            'Judul Standar',
            'Judul Indikator',
            'Unit Audit',
            'Hasil Temuan',
            'Status AMI',
        ];
    }

    public function map($row): array
    {
        $indikator = $row->indikator;
        $standar = $indikator->parent ?? $indikator;

        // Map status label
        $statusLabel = $row->ami_hasil_akhir == 1 ? 'Terpenuhi' : 'Terlampaui';

        return [
            $row->indikorgunit_id, // Use ID for reference
            $indikator->no_indikator ?? '-',
            $standar->indikator ?? '-',
            $indikator->indikator ?? '-',
            $row->orgUnit->name ?? '-',
            strip_tags($row->ami_hasil_temuan ?? '-'),
            $statusLabel,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '198754'], // Success color
            ],
            'alignment' => ['horizontal' => 'center'],
        ]);

        // Color code by status
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $statusCell = $sheet->getCell("G{$row}")->getValue();
            $color = $statusCell === 'Terlampaui' ? 'E7F1FF' : 'D1E7DD';

            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $color],
                ],
            ]);
        }

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Temuan Positif';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 30,
            'D' => 35,
            'E' => 25,
            'F' => 40,
            'G' => 20,
        ];
    }
}
