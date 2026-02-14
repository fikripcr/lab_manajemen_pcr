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

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="inventaris_id">Nama Alat</label>
                            <div class="col-sm-10">
                                <x-tabler.form-select
                                    id="inventaris_id"
                                    name="inventaris_id"
                                    :options="$inventarisList->mapWithKeys(fn($item) => [$item->inventaris_id => $item->nama_alat . ' (' . $item->jenis_alat . ')'])->toArray()"
                                    :selected="old('inventaris_id', $labInventaris->inventaris_id)"
                                    required="true"
                                    type="select2"
                                />
                                @error('inventaris_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="no_series">No Series</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input name="no_series" value="{{ $labInventaris->no_series }}" placeholder="Nomor seri atau kode tambahan" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="tanggal_penempatan">Tanggal Penempatan</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input type="date" name="tanggal_penempatan" value="{{ $labInventaris->tanggal_penempatan?->format('Y-m-d') }}" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="tanggal_penghapusan">Tanggal Penghapusan</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input type="date" name="tanggal_penghapusan" value="{{ $labInventaris->tanggal_penghapusan?->format('Y-m-d') }}" class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="status">Status</label>
                            <div class="col-sm-10">
                                <x-tabler.form-select name="status" :options="['active' => 'Active', 'moved' => 'Moved', 'inactive' => 'Inactive']" value="{{ $labInventaris->status }}" class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="keterangan">Keterangan</label>
                            <div class="col-sm-10">
                                <x-tabler.form-textarea name="keterangan" value="{{ $labInventaris->keterangan }}" rows="3" placeholder="Tambahkan keterangan tambahan" class="mb-0" />
                            </div>
                        </div>

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

