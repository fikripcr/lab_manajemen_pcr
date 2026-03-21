<?php
namespace App\Exports\Pemutu;

use App\Models\Pemutu\PeriodeSpmi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemuanAuditExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle, WithColumnWidths
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

        return [
            $row->indikorgunit_id, // Use ID for reference
            $indikator->no_indikator ?? '-',
            $standar->indikator ?? '-',
            $indikator->indikator ?? '-',
            $row->orgUnit->name ?? '-',
            strip_tags($row->ami_hasil_temuan ?? '-'),
            'KTS (Tidak Terpenuhi)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DC3545'], // Danger color for KTS
            ],
            'alignment' => ['horizontal' => 'center'],
        ]);

        // Alternating row colors
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $color = $row % 2 === 0 ? 'F8F9FA' : 'FFFFFF';
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
        return 'Temuan Audit KTS';
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
