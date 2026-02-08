@extends('layouts.admin.app')
@section('header')
@if(isset($pegawai))
    @include('components.hr.profile-header')
@else
    <x-tabler.page-header title="Data Inpassing" pretitle="HR Management" />
@endif
@endsection

@section('content')
<div class="card">
    @include('pages.hr.data-diri.global-tab-nav')
    <div class="card-body p-0">
        @if(isset($pegawai))
             @include('pages.hr.pegawai.parts._inpassing_list')
        @else
            {{-- Global Table View for Inpassing --}}
            <div class="p-3">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped" id="table-inpassing">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pegawai</th>
                                <th>Golongan</th>
                                <th>Terhitung Mulai Tanggal</th>
                                <th>Nomor SK</th>
                                <th>Tanggal SK</th>
                                <th>Masa Kerja</th>
                                <th>Gaji Pokok</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    $('#table-inpassing').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('hr.inpassing.data') }}", // Need to add this route too
                        columns: [
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                            { data: 'nama_pegawai', name: 'pegawai.nama' },
                            { data: 'golongan', name: 'golonganInpassing.golongan' },
                            { data: 'tmt', name: 'tmt' },
                            { data: 'no_sk', name: 'no_sk' },
                            { data: 'tgl_sk', name: 'tgl_sk' },
                            { 
                                data: 'masa_kerja_tahun', 
                                name: 'masa_kerja_tahun',
                                render: function(data, type, row) {
                                    return (row.masa_kerja_tahun || 0) + ' Tahun ' + (row.masa_kerja_bulan || 0) + ' Bulan';
                                }
                            },
                             { 
                                data: 'gaji_pokok', 
                                name: 'gaji_pokok',
                                render: $.fn.dataTable.render.number('.', ',', 0, 'Rp ')
                            },
                        ]
                    });
                });
            </script>
            @endpush
        @endif
    </div>
</div>
@endsection
