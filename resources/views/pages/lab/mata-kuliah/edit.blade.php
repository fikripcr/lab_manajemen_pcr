@if(request()->ajax() || request()->has('ajax'))
    <x-tabler.form-modal
        title="Edit Mata Kuliah"
        route="{{ route('lab.mata-kuliah.update', $mataKuliah->encrypted_mata_kuliah_id) }}"
        method="PUT"
        submitText="Update"
    >
        <x-tabler.form-input name="kode_mk" label="Kode MK" value="{{ old('kode_mk', $mataKuliah->kode_mk) }}" placeholder="e.g. IF101" required />
        <x-tabler.form-input name="nama_mk" label="Nama MK" value="{{ old('nama_mk', $mataKuliah->nama_mk) }}" placeholder="e.g. Pemrograman Web" required />
        <x-tabler.form-input type="number" name="sks" label="SKS" value="{{ old('sks', $mataKuliah->sks) }}" min="1" max="6" required />
    </x-tabler.form-modal>
@else
    @extends('layouts.admin.app')

    @section('header')
        <x-tabler.page-header title="Edit Mata Kuliah" pretitle="Mata Kuliah">
            <x-slot:actions>
                <x-tabler.button type="back" :href="route('lab.mata-kuliah.index')" />
            </x-slot:actions>
        </x-tabler.page-header>
    @endsection

    @section('content')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <x-tabler.flash-message />

                        <form method="POST" action="{{ route('lab.mata-kuliah.update', $mataKuliah->encrypted_mata_kuliah_id) }}" class="ajax-form">
                            @csrf
                            @method('PUT')

                            <x-tabler.form-input name="kode_mk" label="Kode MK" value="{{ old('kode_mk', $mataKuliah->kode_mk) }}" placeholder="e.g. IF101" required />

                            <x-tabler.form-input name="nama_mk" label="Nama MK" value="{{ old('nama_mk', $mataKuliah->nama_mk) }}" placeholder="e.g. Pemrograman Web" required />

                            <x-tabler.form-input type="number" name="sks" label="SKS" value="{{ old('sks', $mataKuliah->sks) }}" min="1" max="6" required />

                            <div class="row mt-4">
                                <div class="col-sm-10 offset-sm-2">
                                    <x-tabler.button type="submit" text="Update" />
                                    <x-tabler.button type="cancel" :href="route('lab.mata-kuliah.index')" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif
