@extends('layouts.admin.app')

@section('header')
    <x-sys.page-header :title="$inventory->nama_alat" pretitle="Inventory Details">
        <x-slot:actions>
            <x-sys.button type="edit" :href="route('inventaris.edit', $inventory)" />
            <x-sys.button type="back" :href="route('inventaris.index')" />
        </x-slot:actions>
    </x-sys.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-admin.flash-message />

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
                        <x-sys.button type="delete" 
                                    class="ajax-delete"
                                    :data-url="route('inventaris.destroy', $inventory)"
                                    data-title="Hapus Inventaris"
                                    data-text="Apakah Anda yakin ingin menghapus item inventaris ini? Data terkait akan ikut terhapus."
                                    data-redirect="{{ route('inventaris.index') }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
