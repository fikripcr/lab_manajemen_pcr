@extends('layouts.admin.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Tables /</span> Inventory Management</h4>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('inventaris.create') }}" class="btn btn-primary me-2">
                <i class="bx bx-plus"></i> Add New Inventory
            </a>
            <button type="button" class="btn btn-success" id="exportBtn">
                <i class="bx bx-export"></i> Export Excel
            </button>
        </div>
    </div>
    <style>
/*
 * =========================================
 * FINAL - KODE CSS GABUNGAN UNTUK CHOICES.JS
 * =========================================
 * Hapus semua style choices.js lama Anda, ganti dengan ini.
*/

/* 1. KOTAK UTAMA (INPUT FIELD) */
.choices__inner {
  background-color: #ffffff;
  border: 1px solid #ced4da;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 14px;
}

/* 2. TEKS DI DALAM KOTAK (ITEM TERPILIH) */
.choices__list--single .choices__item {
  color: #212529;
}

/* 3. PANAH DROPDOWN */
.choices[data-type*="select-one"]::after {
  border-color: #333 transparent transparent;
  right: 12px;
  margin-top: -3px;
}

/* 4. STYLE SAAT FOKUS (DIKLIK) - Versi HALUS, BUKAN BIRU */
.choices.is-focused .choices__inner {
  border-color: #ddd;
  /* Bayangan halus, BUKAN glow biru tebal */
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  /* atau 'box-shadow: none;' jika tidak mau ada efek sama sekali */
}

/* 5. MENU DROPDOWN "MODERN" (INI YANG PENTING) */
.choices__list--dropdown {
  /* Sudut melengkung */
  border-radius: 8px;

  /* Efek bayangan (shadow) */
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);

  /* Border halus */
  border: 1px solid #e2e8f0;

  /* Jarak dari kotak atas */
  margin-top: 6px;
}

/* 6. INPUT "SEARCH..." DI DALAM DROPDOWN */
.choices__list--dropdown .choices__input {
  background-color: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  padding: 8px 12px;
  font-size: 14px;
}

/* 7. EFEK HOVER PADA ITEM PILIHAN */
.choices__list--dropdown .choices__item--selectable.is-highlighted {
  background-color: #f3f4f6; /* Warna latar hover */
  color: #111827;
}

/* 8. Menghilangkan border aneh di atas item pertama */
.choices__list--dropdown .choices__item--choice:first-child {
  border-top: 0;
}
    </style>
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center py-2">
                <h5 class="mb-2 mb-sm-0">Inventaris List</h5>
                <div class="d-flex flex-wrap gap-2">
                    <div class="me-3 mb-2 mb-sm-0">
                        <x-admin.datatable-page-length id="pageLength" selected="10" />
                    </div>
                </div>
            </div>
            @include('components.datatable.search-filter', [
                'dataTableId' => 'inventaris-table',
                'filters' => [
                    [
                        'id' => 'conditionFilter',
                        'name' => 'condition',
                        'label' => 'Condition',
                        'type' => 'select',
                        'column' => '',
                        'options' => [
                            '' => 'All Conditions',
                            'Baik' => 'Good',
                            'Rusak Ringan' => 'Minor Damage',
                            'Rusak Berat' => 'Major Damage',
                            'Tidak Dapat Digunakan' => 'Cannot Be Used'
                        ],
                        'placeholder' => 'Select Condition',
                        'class' => 'choice-select'
                    ]
                ]
            ])
        </div>
        <div class="card-body">
            <x-admin.flash-message />
            <div class="table-responsive">
                <table id="inventaris-table" class="table  " style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Equipment Name</th>
                            <th>Type</th>
                            <th>Condition</th>
                            <th>Last Check</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!$.fn.DataTable.isDataTable('#inventaris-table')) {
                var table = $('#inventaris-table').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    ajax: {
                        url: '{{ route('inventaris.data') }}',
                        data: function(d) {
                            // Capture custom search from the filter component
                            var searchValue = $('#globalSearch-inventaris-table').val();
                            if (searchValue) {
                                d.search.value = searchValue;
                            }

                            // Add condition filter to the request
                            d.condition = $('#conditionFilter').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'nama_alat',
                            name: 'nama_alat',
                            render: function(data, type, row) {
                                return '<span class="fw-medium">' + data + '</span>';
                            }
                        },
                        {
                            data: 'jenis_alat',
                            name: 'jenis_alat',
                            render: function(data, type, row) {
                                return '<span class="badge bg-label-info me-1">' + data + '</span>';
                            }
                        },
                        {
                            data: 'kondisi_terakhir',
                            name: 'kondisi_terakhir'
                        },
                        {
                            data: 'tanggal_pengecekan',
                            name: 'tanggal_pengecekan',
                            render: function(data) {
                                return data ? data : '-';
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    order: [
                        [0, 'desc']
                    ],
                    pageLength: 10,
                    responsive: true,
                    dom: 'rtip' // Only show table, info, and paging
                });

                // Handle condition filter change
                $(document).on('change', '#conditionFilter', function() {
                    table.ajax.reload();
                });

                // Setup common DataTable behaviors
                setupCommonDataTableBehaviors(table, {
                    searchInputSelector: '#globalSearch-inventaris-table',
                    pageLengthSelector: '#pageLength'
                });

                // Handle export button click
                $('#exportBtn').on('click', function() {
                    // Get current search and filter values from the search filter component
                    var searchValue = $('#globalSearch-inventaris-table').val();
                    var conditionValue = $('#conditionFilter').val();

                    // Build query parameters
                    var params = new URLSearchParams();
                    if(searchValue) {
                        params.append('search', searchValue);
                    }
                    if(conditionValue) {
                        params.append('condition', conditionValue);
                    }

                    // Redirect to export URL with parameters
                    window.location.href = '{{ route('inventaris.export') }}?' + params.toString();
                });
            }
        });
    </script>
@endpush>
