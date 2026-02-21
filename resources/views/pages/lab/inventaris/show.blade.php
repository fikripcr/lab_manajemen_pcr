@if(request()->ajax() || request()->has('ajax'))
    <div class="modal-header">
        <h5 class="modal-title">Inventory Details: {{ $inventory->nama_alat }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="datagrid">
            <div class="datagrid-item">
                <div class="datagrid-title">Equipment Name</div>
                <div class="datagrid-content">{{ $inventory->nama_alat }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Type</div>
                <div class="datagrid-content">{{ $inventory->jenis_alat }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Condition</div>
                <div class="datagrid-content">
                    <span class="badge bg-{{ $inventory->kondisi_terakhir === 'Baik' ? 'success' : ($inventory->kondisi_terakhir === 'Rusak Ringan' ? 'warning' : 'danger') }}">
                        {{ $inventory->kondisi_terakhir }}
                    </span>
                </div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Lab</div>
                <div class="datagrid-content">{{ $inventory->lab->name ?? '-' }}</div>
            </div>
            <div class="datagrid-item">
                <div class="datagrid-title">Last Check Date</div>
                <div class="datagrid-content">{{ $inventory->tanggal_pengecekan->format('d M Y') }}</div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="cancel" data-bs-dismiss="modal" text="Tutup" />
        <x-tabler.button type="edit" :href="route('lab.inventaris.edit', $inventory)" />
    </div>
@else
    @extends('layouts.tabler.app')

    @section('header')
        <x-tabler.page-header :title="$inventory->nama_alat" pretitle="Inventory Details">
            <x-slot:actions>
                <x-tabler.button type="edit" :href="route('lab.inventaris.edit', $inventory)" />
                <x-tabler.button type="back" :href="route('lab.inventaris.index')" />
            </x-slot:actions>
        </x-tabler.page-header>
    @endsection

    @section('content')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <x-tabler.flash-message />

                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Equipment Name</div>
                                <div class="datagrid-content">{{ $inventory->nama_alat }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Type</div>
                                <div class="datagrid-content">{{ $inventory->jenis_alat }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Condition</div>
                                <div class="datagrid-content">
                                    <span class="badge bg-{{ $inventory->kondisi_terakhir === 'Baik' ? 'success' : ($inventory->kondisi_terakhir === 'Rusak Ringan' ? 'warning' : 'danger') }}">
                                        {{ $inventory->kondisi_terakhir }}
                                    </span>
                                </div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Lab</div>
                                <div class="datagrid-content">{{ $inventory->lab->name ?? '-' }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Last Check Date</div>
                                <div class="datagrid-content">{{ $inventory->tanggal_pengecekan->format('d M Y') }}</div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <x-tabler.button type="button" icon="ti ti-trash" class="btn-danger ajax-delete" 
                                        :data-url="route('lab.inventaris.destroy', $inventory)"
                                        data-title="Hapus Inventaris"
                                        data-text="Apakah Anda yakin ingin menghapus item inventaris ini? Data terkait akan ikut terhapus."
                                        data-redirect="{{ route('lab.inventaris.index') }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif
