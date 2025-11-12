<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenericModelExport implements FromQuery, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithEvents
{
    protected $modelClass;
    protected $columns;
    protected $filters;
    protected $joins;
    protected $withRelationships;

    public function __construct(string $modelClass, array $columns = [], array $filters = [], array $joins = [], array $withRelationships = [])
    {
        $this->modelClass = $modelClass;
        $this->columns = $columns ?: ['id', 'name', 'created_at']; // Default columns
        $this->filters = $filters;
        $this->joins = $joins;
        $this->withRelationships = $withRelationships;
    }

    /**
     * Query to fetch data for export
     */
    public function query()
    {
        $modelInstance = new $this->modelClass;
        $query = $modelInstance->newQuery();

        // Apply joins if specified
        foreach ($this->joins as $join) {
            $query->leftJoin($join['table'], $join['first'], $join['operator'], $join['second']);
        }

        // Apply filters if provided
        $this->applyFilters($query);

        // Add selected fields to the query
        $selectFields = [];
        foreach ($this->columns as $column) {
            if (strpos($column, '.') !== false) {
                // If it's a joined table field, use raw SQL
                $selectFields[] = DB::raw("$column AS " . str_replace('.', '_', $column));
            } else {
                $selectFields[] = $column;
            }
        }
        $query->select($selectFields);

        return $query;
    }

    /**
     * Apply filters to the query
     */
    protected function applyFilters($query)
    {
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $columnsToSearch = array_filter($this->columns, function ($column) {
                    return strpos($column, '.') === false; // Only search non-joined fields
                });
                
                foreach ($columnsToSearch as $column) {
                    $q->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
        }

        // Add any specific filters if provided
        if (!empty($this->filters['where'])) {
            foreach ($this->filters['where'] as $field => $value) {
                $query->where($field, $value);
            }
        }

        if (!empty($this->filters['whereIn'])) {
            foreach ($this->filters['whereIn'] as $field => $values) {
                $query->whereIn($field, $values);
            }
        }
    }

    /**
     * Define headings for the export
     */
    public function headings(): array
    {
        $headings = [];
        foreach ($this->columns as $column) {
            // Convert field names to readable format
            $heading = ucfirst(str_replace(['_', '.'], [' ', ' '], $column));
            $headings[] = $heading;
        }

        return $headings;
    }

    /**
     * Map the data for each row
     */
    public function map($model): array
    {
        $row = [];
        foreach ($this->columns as $column) {
            if (strpos($column, '.') !== false) {
                // Handle joined table field
                $field = str_replace('.', '_', $column);
                $row[] = $model->$field ?? '';
            } else {
                $row[] = $model->$column ?? '';
            }
        }

        return $row;
    }

    /**
     * Define column widths (default)
     */
    public function columnWidths(): array
    {
        $widths = [];
        foreach (range('A', chr(ord('A') + count($this->columns) - 1)) as $index => $column) {
            $widths[$column] = 20; // Default width for all columns
        }
        return $widths;
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
            AfterSheet::class => function(AfterSheet $event) {
                // Freeze the header row
                $event->sheet->getDelegate()->freezePane('A2');
            },
        ];
    }
}