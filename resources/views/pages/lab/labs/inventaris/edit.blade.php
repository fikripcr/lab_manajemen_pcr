@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('title', 'Edit Inventaris Lab: ' . $labInventaris->kode_inventaris)

@section('header')
    <x-tabler.page-header :title="'Edit Inventaris Lab: ' . $labInventaris->kode_inventaris" pretitle="Laboratorium">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.labs.inventaris.index', $labInventaris->encrypted_lab_id)" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form action="{{ route('lab.labs.inventaris.update', [$labInventaris->encrypted_lab_id, $labInventaris->encrypted_id]) }}" method="POST" class="ajax-form">
                        @csrf
                        @method('PUT')

                        <x-tabler.form-select
                            id="inventaris_id"
                            name="inventaris_id"
                            label="Nama Alat"
                            :options="$inventarisList->mapWithKeys(fn($item) => [$item->inventaris_id => $item->nama_alat . ' (' . $item->jenis_alat . ')'])->toArray()"
                            :selected="old('inventaris_id', $labInventaris->inventaris_id)"
                            required="true"
                            type="select2"
                        />
                        @error('inventaris_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <x-tabler.form-input name="no_series" label="No Series" value="{{ $labInventaris->no_series }}" placeholder="Nomor seri atau kode tambahan" required />

                        <x-tabler.form-input type="date" name="tanggal_penempatan" label="Tanggal Penempatan" value="{{ $labInventaris->tanggal_penempatan?->format('Y-m-d') }}" required />

                        <x-tabler.form-input type="date" name="tanggal_penghapusan" label="Tanggal Penghapusan" value="{{ $labInventaris->tanggal_penghapusan?->format('Y-m-d') }}" />

                        <x-tabler.form-select name="status" label="Status" :options="['active' => 'Active', 'moved' => 'Moved', 'inactive' => 'Inactive']" value="{{ $labInventaris->status }}" />

                        <x-tabler.form-textarea name="keterangan" label="Keterangan" value="{{ $labInventaris->keterangan }}" rows="3" placeholder="Tambahkan keterangan tambahan" />

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" text="Simpan Perubahan" />
                                <x-tabler.button type="cancel" :href="route('lab.labs.inventaris.index', $labInventaris->encrypted_lab_id)" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

