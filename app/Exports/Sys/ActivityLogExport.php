<?php

namespace App\Exports\Sys;

use App\Models\Sys\Activity;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ActivityLogExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Activity::with(['causer:id,name', 'subject']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Log Name',
            'Description',
            'Subject Type',
            'Causer Name',
            'Created At',
        ];
    }

    public function map($log): array
    {
        return [
            $log->id,
            $log->log_name,
            $log->description,
            $log->subject_type,
            $log->causer->name ?? 'N/A',
            $log->created_at,
        ];
    }
}