@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
    <x-tabler.page-header title="Create New Mata Kuliah" pretitle="Mata Kuliah">
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

                    <form method="POST" action="{{ route('lab.mata-kuliah.store') }}" class="ajax-form">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="kode_mk">Kode MK</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input name="kode_mk" value="{{ old('kode_mk') }}" placeholder="e.g. IF101" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="nama_mk">Nama MK</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input name="nama_mk" value="{{ old('nama_mk') }}" placeholder="e.g. Pemrograman Web" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="sks">SKS</label>
                            <div class="col-sm-10">
                                <x-tabler.form-input type="number" name="sks" value="{{ old('sks', 3) }}" min="1" max="6" required class="mb-0" />
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" text="Save" />
                                <x-tabler.button type="cancel" :href="route('lab.mata-kuliah.index')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
