@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Riwayat Presensi" pretitle="HR Module">
    <x-slot:actions>
        <x-tabler.button href="{{ route('hr.presensi.index') }}" class="btn-outline-primary" icon="ti ti-arrow-left" text="Kembali" />
        <x-tabler.button class="btn-outline-success" id="btn-export" icon="ti ti-download" text="Export" />
        
        <x-tabler.datatable-filter :dataTableId="'presensi-history-table'">
            <div class="row g-2">
                <div class="col-12">
                    <x-tabler.form-select id="filter-month" name="month" label="Bulan" class="mb-0">
                        <option value="all">Semua Bulan</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ sprintf('%02d', $m) }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                        @endforeach
                    </x-tabler.form-select>
                </div>
                <div class="col-12">
                    <x-tabler.form-select id="filter-year" name="year" label="Tahun" class="mb-0">
                        <option value="all">Semua Tahun</option>
                        @foreach(range(date('Y'), date('Y') - 5) as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </x-tabler.form-select>
                </div>
                <div class="col-12">
                    <x-tabler.form-select id="filter-status" name="status" label="Status" class="mb-0">
                        <option value="all">Semua Status</option>
                        <option value="on_time">Tepat Waktu</option>
                        <option value="late">Terlambat</option>
                        <option value="absent">Tidak Hadir</option>
                    </x-tabler.form-select>
                </div>
            </div>
        </x-tabler.datatable-filter>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex flex-wrap gap-2">
            <div>
                <x-tabler.datatable-page-length :dataTableId="'presensi-history-table'" />
            </div>
            <div>
                <x-tabler.datatable-search :dataTableId="'presensi-history-table'" />
            </div>
        </div>
    </div>
    <div class="card-body">
        <x-tabler.flash-message />
        <x-tabler.datatable
            id="presensi-history-table"
            route="{{ route('hr.presensi.history-data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                ['data' => 'date', 'name' => 'date', 'title' => 'Tanggal', 'class' => 'text-center'],
                ['data' => 'check_in', 'name' => 'check_in', 'title' => 'Check In', 'class' => 'text-center'],
                ['data' => 'check_out', 'name' => 'check_out', 'title' => 'Check Out', 'class' => 'text-center'],
                ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'class' => 'text-center'],
                ['data' => 'address', 'name' => 'address', 'title' => 'Lokasi'],
                ['data' => 'duration', 'name' => 'duration', 'title' => 'Durasi', 'class' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
            ]"
        />
    </div>
</div>

{{-- Modal is now loaded via AJAX --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ === 'undefined') {
        console.error('jQuery not loaded yet');
        return;
    }

    // Export button
    $('#btn-export').click(function() {
        const month = $('#filter-month').val();
        const year = $('#filter-year').val();
        const status = $('#filter-status').val();

        let params = new URLSearchParams();
        if (month && month !== 'all') params.append('month', month);
        if (year && year !== 'all') params.append('year', year);
        if (status && status !== 'all') params.append('status', status);

        const url = '{{ route('hr.presensi.export') }}?' + params.toString();
        window.open(url, '_blank');
    });

    // Custom render functions for DataTable
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        }
    });
});

// Custom DataTable column renderers
const presensiColumnDefs = [
    {
        targets: 2, // Check In column
        render: function(data, type, row) {
            if (data && data !== '--:--') {
                return `<span class="badge bg-success text-white">${data}</span>`;
            }
            return '<span class="text-muted">--:--</span>';
        }
    },
    {
        targets: 3, // Check Out column
        render: function(data, type, row) {
            if (data && data !== '--:--') {
                return `<span class="badge bg-danger">${data}</span>`;
            }
            return '<span class="text-muted">--:--</span>';
        }
    },
    {
        targets: 4, // Status column
        render: function(data, type, row) {
            const statusMap = {
                'on_time': '<span class="badge bg-success text-white">Tepat Waktu</span>',
                'late': '<span class="badge bg-warning text-white">Terlambat</span>',
                'absent': '<span class="badge bg-danger">Tidak Hadir</span>',
                'early_checkout': '<span class="badge bg-info">Pulang Awal</span>'
            };
            return statusMap[data] || '<span class="badge bg-secondary text-white">-</span>';
        }
    },
    {
        targets: 5, // Address column
        render: function(data, type, row) {
            if (data && data.length > 50) {
                return data.substring(0, 50) + '...';
            }
            return data || '-';
        }
    },
    {
        targets: 6, // Duration column
        render: function(data, type, row) {
            if (data) {
                return `<span class="fw-bold">${data}</span>`;
            }
            return '-';
        }
    },
    {
        targets: 7, // Action column
        render: function(data, type, row) {
            const detailUrl = '{{ route("hr.presensi.history.show", ":date") }}'.replace(':date', row.date);
            return `
                <div class="btn-list">
                    <x-tabler.button class="btn-sm btn-outline-primary ajax-modal-btn" data-url="${detailUrl}" icon="ti ti-eye" />
                </div>
            `;
        }
    }
];

// Apply column definitions when DataTable is initialized
$(document).ready(function() {
    $('#presensi-history-table').on('init.dt', function() {
        const table = $('#presensi-history-table').DataTable();
        table.settings().aoColumnDefs = presensiColumnDefs;
    });
});

</script>
@endsection
