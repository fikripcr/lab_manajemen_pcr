@extends('layouts.admin.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col">
        <h2 class="page-title">
            Riwayat Presensi
        </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="{{ route('hr.presensi.index') }}" class="btn btn-outline-primary">
                <i class="ti ti-arrow-left me-2"></i>
                Kembali
            </a>
            <button class="btn btn-outline-success" id="btn-export">
                <i class="ti ti-download me-2"></i>
                Export
            </button>
        </div>
    </div>
</div>
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
            <div class="ms-auto">
                <div class="row g-2">
                    <div class="col-auto">
                        <x-tabler.form-select id="filter-month" label="Bulan" class="mb-0" style="width: 150px;">
                            <option value="">Semua Bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </x-tabler.form-select>
                    </div>
                    <div class="col-auto">
                        <x-tabler.form-select id="filter-year" label="Tahun" class="mb-0" style="width: 120px;">
                            <option value="">Semua Tahun</option>
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                        </x-tabler.form-select>
                    </div>
                    <div class="col-auto">
                        <x-tabler.form-select id="filter-status" label="Status" class="mb-0" style="width: 150px;">
                            <option value="">Semua Status</option>
                            <option value="on_time">Tepat Waktu</option>
                            <option value="late">Terlambat</option>
                            <option value="absent">Tidak Hadir</option>
                        </x-tabler.form-select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <x-tabler.flash-message />
        <x-tabler.datatable 
            id="presensi-history-table"
            route="{{ route('hr.presensi.history-data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
                ['data' => 'date', 'name' => 'date', 'title' => 'Tanggal', 'className' => 'text-center'],
                ['data' => 'check_in', 'name' => 'check_in', 'title' => 'Check In', 'className' => 'text-center'],
                ['data' => 'check_out', 'name' => 'check_out', 'title' => 'Check Out', 'className' => 'text-center'],
                ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'className' => 'text-center'],
                ['data' => 'address', 'name' => 'address', 'title' => 'Lokasi'],
                ['data' => 'duration', 'name' => 'duration', 'title' => 'Durasi', 'className' => 'text-center'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
            ]"
        />
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Presensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detail-content">
                    <!-- Detail content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ === 'undefined') {
        console.error('jQuery not loaded yet');
        return;
    }
    
    initializeHistory();
});

function initializeHistory() {
    // Set up filter change events
    $('#filter-month, #filter-year, #filter-status').on('change', function() {
        $('#presensi-history-table').DataTable().ajax.reload();
    });
    
    // Export button
    $('#btn-export').click(handleExport);
    
    // Custom render functions for DataTable
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
        }
    });
}

function handleExport() {
    const month = $('#filter-month').val();
    const year = $('#filter-year').val();
    const status = $('#filter-status').val();
    
    let params = new URLSearchParams();
    if (month) params.append('month', month);
    if (year) params.append('year', year);
    if (status) params.append('status', status);
    
    const url = '{{ route('hr.presensi.export') }}?' + params.toString();
    window.open(url, '_blank');
}

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
            return `
                <div class="btn-list">
                    <button class="btn btn-sm btn-outline-primary" onclick="showDetail('${row.date}')">
                        <i class="ti ti-eye"></i>
                    </button>
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

function showDetail(date) {
    // Mock detail data - in real app, this would fetch from API
    const mockDetail = {
        date: date,
        checkIn: '08:15:00',
        checkOut: '17:30:00',
        status: 'on_time',
        checkInLocation: {
            latitude: -6.208763,
            longitude: 106.845599,
            address: 'Jakarta, Indonesia'
        },
        checkOutLocation: {
            latitude: -6.208763,
            longitude: 106.845599,
            address: 'Jakarta, Indonesia'
        },
        duration: '9 jam 15 menit',
        shift: 'Reguler (08:00 - 17:00)',
        notes: '-'
    };
    
    const detailHtml = `
        <div class="row">
            <div class="col-md-6">
                <h6>Informasi Presensi</h6>
                <table class="table table-sm">
                    <tr>
                        <td><strong>Tanggal:</strong></td>
                        <td>${mockDetail.date}</td>
                    </tr>
                    <tr>
                        <td><strong>Shift:</strong></td>
                        <td>${mockDetail.shift}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td><span class="badge bg-success text-white">Tepat Waktu</span></td>
                    </tr>
                    <tr>
                        <td><strong>Durasi:</strong></td>
                        <td>${mockDetail.duration}</td>
                    </tr>
                    <tr>
                        <td><strong>Catatan:</strong></td>
                        <td>${mockDetail.notes}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Lokasi Check In</h6>
                <table class="table table-sm">
                    <tr>
                        <td><strong>Waktu:</strong></td>
                        <td>${mockDetail.checkIn}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat:</strong></td>
                        <td>${mockDetail.checkInLocation.address}</td>
                    </tr>
                    <tr>
                        <td><strong>Koordinat:</strong></td>
                        <td>${mockDetail.checkInLocation.latitude}, ${mockDetail.checkInLocation.longitude}</td>
                    </tr>
                </table>
                
                <h6 class="mt-3">Lokasi Check Out</h6>
                <table class="table table-sm">
                    <tr>
                        <td><strong>Waktu:</strong></td>
                        <td>${mockDetail.checkOut}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat:</strong></td>
                        <td>${mockDetail.checkOutLocation.address}</td>
                    </tr>
                    <tr>
                        <td><strong>Koordinat:</strong></td>
                        <td>${mockDetail.checkOutLocation.latitude}, ${mockDetail.checkOutLocation.longitude}</td>
                    </tr>
                </table>
            </div>
        </div>
    `;
    
    $('#detail-content').html(detailHtml);
    $('#detailModal').modal('show');
}
</script>
@endsection
