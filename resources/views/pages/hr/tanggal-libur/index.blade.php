@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Tanggal Libur" pretitle="HR">
    <x-slot:actions>
        <form action="{{ route('hr.tanggal-libur.index') }}" method="GET" class="d-inline-block me-2 text-start">
            <x-tabler.form-select name="tahun" class="mb-0" onchange="this.form.submit()">
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </x-tabler.form-select>
        </form>
        <x-tabler.button 
            type="create" 
            class="ajax-modal-btn d-none d-sm-inline-block" 
            data-url="{{ route('hr.tanggal-libur.create') }}" 
            data-modal-title="Tambah Tanggal Libur"
            data-modal-size="modal-lg" 
            text="Tambah Tanggal" 
        />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

        @if($data->isEmpty())
            <x-tabler.empty-state 
                title="Tidak ada data" 
                text="Belum ada tanggal libur yang terdaftar untuk tahun {{ $tahun }}."
            />
        @else
            <div class="row row-cards">
                @foreach($data as $item)
                <div class="col-sm-6 col-lg-3">
                    <x-tabler.card class="card-sm">
                        <x-tabler.card-body>
                            <div class="d-flex align-items-center mb-2">
                                <div class="subheader">{{ $item->tgl_libur ? \Carbon\Carbon::parse($item->tgl_libur)->isoFormat('dddd') : '-' }}</div>
                                <div class="ms-auto">
                                    <x-tabler.dropdown placement="end">
                                        <x-tabler.dropdown-item type="delete" url="{{ route('hr.tanggal-libur.destroy', $item->tanggallibur_id) }}" />
                                    </x-tabler.dropdown>
                                </div>
                            </div>
                            <div class="h1 mb-1">{{ $item->tgl_libur ? \Carbon\Carbon::parse($item->tgl_libur)->format('d M') : '-' }}</div>
                            <div class="text-muted">{{ $item->keterangan }}</div>
                        </x-tabler.card-body>
                    </x-tabler.card>
                </div>
                @endforeach
            </div>
        @endif
@endsection
