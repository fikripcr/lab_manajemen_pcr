<?php
namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class UserExport implements FromQuery, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithEvents
{
    protected $filters;
    protected $columns;

    public function __construct($filters = [], $columns = [])
    {
        $this->filters = $filters;
        $this->columns = ! empty($columns) ? $columns : ['name', 'email', 'role', 'npm', 'nip'];
    }

    /**
     * Query to fetch data for export
     */
    public function query()
    {
        $query = User::query()->select([
            'users.name',
            'users.email',
            'users.google_id',
            'users.npm',
            'users.nip',
            'users.avatar',
            'users.email_verified_at',
        ])
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id');

        // Apply search filter if provided
        if (! empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'LIKE', "%{$search}%")
                    ->orWhere('users.email', 'LIKE', "%{$search}%")
                    ->orWhere('roles.name', 'LIKE', "%{$search}%")
                    ->orWhere('users.npm', 'LIKE', "%{$search}%")
                    ->orWhere('users.nip', 'LIKE', "%{$search}%");
            });
        }

        // Add the role name to the selection
        $query->addSelect(DB::raw('roles.name as role_name'));

        return $query;
    }

    /**
     * Define headings for the export
     */
    public function headings(): array
    {
        $headingMap = [
            'id'              => 'ID',
            'name'            => 'Name',
            'email'           => 'Email',
            'google_id'       => 'Google ID',
            'npm'             => 'NPM',
            'nip'             => 'NIP',
            'avatar'          => 'Avatar',
            'email_verified_at' => 'Email Verified At',
            'created_at'      => 'Created At',
            'updated_at'      => 'Updated At',
            'role_name'       => 'Role',
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
    public function map($user): array
    {
        $dataMap = [
            'id'              => $user->id,
            'name'            => $user->name,
            'email'           => $user->email,
            'google_id'       => $user->google_id ?: '-',
            'npm'             => $user->npm ?: '-',
            'nip'             => $user->nip ?: '-',
            'avatar'          => $user->avatar ?: '-',
            'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : '-',
            'created_at'      => $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '',
            'updated_at'      => $user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : '',
            'role_name'       => $user->role_name ?: 'No Role',
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
            'B' => 20, // Name
            'C' => 30, // Email
            'D' => 20, // Google ID
            'E' => 15, // NPM
            'F' => 15, // NIP
            'G' => 40, // Avatar
            'H' => 20, // Email Verified At
            'I' => 20, // Created At
            'J' => 20, // Updated At
            'K' => 15, // Role
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
