<?php
namespace App\Exports;

use App\Models\Inventaris;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventarisExport implements FromQuery, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithEvents
{
    protected $filters;
    protected $columns;

    public function __construct($filters = [], $columns = [])
    {
        $this->filters = $filters;
        $this->columns = ! empty($columns) ? $columns : ['nama_alat', 'jenis_alat', 'kondisi_terakhir', 'tanggal_pengecekan', 'lab_name'];
    }

    /**
     * Query to fetch data for export
     */
    public function query()
    {
        $query = Inventaris::query()->select('inventaris.*')
            ->leftJoin('labs', 'inventaris.lab_id', '=', 'labs.lab_id');

        // Apply search filter if provided
        if (! empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('inventaris.nama_alat', 'LIKE', "%{$search}%")
                    ->orWhere('inventaris.jenis_alat', 'LIKE', "%{$search}%")
                    ->orWhere('inventaris.kondisi_terakhir', 'LIKE', "%{$search}%")
                    ->orWhere('labs.name', 'LIKE', "%{$search}%");
            });
        }

        // Apply condition filter if provided
        if (! empty($this->filters['condition'])) {
            $query->where('inventaris.kondisi_terakhir', $this->filters['condition']);
        }

        // Apply lab filter if provided
        if (! empty($this->filters['lab_id'])) {
            $query->where('inventaris.lab_id', $this->filters['lab_id']);
        }

        // Add the lab name to the selection
        $query->addSelect(DB::raw('labs.name as lab_name'));

        return $query;
    }

    /**
     * Define headings for the export
     */
    public function headings(): array
    {
        $headingMap = [
            'id'                 => 'ID',
            'nama_alat'          => 'Equipment Name',
            'jenis_alat'         => 'Equipment Type',
            'kondisi_terakhir'   => 'Condition',
            'tanggal_pengecekan' => 'Check Date',
            'lab_name'           => 'Lab',
        ];

        $headings = [];
        foreach ($this->columns as $column) {
            $headings[] = $headingMap[$column] ?? ucfirst(str_replace('_', ' ', $column));
        }

        return $headings;
    }

    /**
     * Map the data for each row
     */
    public function map($inventaris): array
    {
        $dataMap = [
            'id'                 => $inventaris->id,
            'nama_alat'          => $inventaris->nama_alat,
            'jenis_alat'         => $inventaris->jenis_alat,
            'kondisi_terakhir'   => $inventaris->kondisi_terakhir,
            'tanggal_pengecekan' => $inventaris->tanggal_pengecekan ? $inventaris->tanggal_pengecekan->format('Y-m-d') : '',
            'lab_name'           => $inventaris->lab_name ?: '-',
        ];

        $row = [];
        foreach ($this->columns as $column) {
            $row[] = $dataMap[$column] ?? '';
        }

        return $row;
    }

    /**
     * Define column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10, // ID
            'B' => 25, // Equipment Name
            'C' => 20, // Equipment Type
            'D' => 15, // Condition
            'E' => 15, // Check Date
            'F' => 25, // Lab
        ];
    }

    /**
     * Apply styles to the sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Make heading row bold
        ];
    }

    /**
     * Register events
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Freeze the header row
                $event->sheet->getDelegate()->freezePane('A2');
            },
        ];
    }
}
