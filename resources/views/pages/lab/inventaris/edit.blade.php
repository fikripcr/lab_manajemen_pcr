@if(request()->ajax())
    <form class="ajax-form" action="{{ route('lab.inventaris.update', $inventory) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Edit Inventory</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <x-tabler.flash-message />

            <div class="mb-3">
                <x-tabler.form-select name="lab_id" label="Lab" :options="$labs->pluck('name', 'lab_id')->toArray()" selected="{{ old('lab_id', $inventory->lab_id) }}" placeholder="Select Lab" required />
            </div>

            <x-tabler.form-input name="nama_alat" label="Equipment Name" value="{{ $inventory->nama_alat }}" placeholder="e.g., Laptop, Microscope, etc." required />

            <x-tabler.form-input name="jenis_alat" label="Type" value="{{ $inventory->jenis_alat }}" placeholder="e.g., Electronic, Chemical, Equipment" required />

            <div class="mb-3">
                <x-tabler.form-select name="kondisi_terakhir" label="Condition" :options="['Baik' => 'Good', 'Rusak Ringan' => 'Minor Damage', 'Rusak Berat' => 'Major Damage', 'Tidak Dapat Digunakan' => 'Cannot Be Used']" selected="{{ old('kondisi_terakhir', $inventory->kondisi_terakhir) }}" placeholder="Select Condition" required />
            </div>

            <x-tabler.form-input type="date" name="tanggal_pengecekan" label="Last Check Date" value="{{ $inventory->tanggal_pengecekan ? $inventory->tanggal_pengecekan->format('Y-m-d') : '' }}" required />
        </div>
        <div class="modal-footer">
            <x-tabler.button type="cancel" data-bs-dismiss="modal" />
            <x-tabler.button type="submit" text="Update Inventory" />
        </div>
    </form>
@else
    @extends('layouts.admin.app')

    @section('header')
        <x-tabler.page-header title="Edit Inventory" pretitle="Inventory">
            <x-slot:actions>
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

                        <form action="{{ route('lab.inventaris.update', $inventory) }}" method="POST" class="ajax-form">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label required" for="lab_id">Lab</label>
                                <div class="col-sm-10">
                                    <x-tabler.form-select name="lab_id" :options="$labs->pluck('name', 'lab_id')->toArray()" selected="{{ old('lab_id', $inventory->lab_id) }}" placeholder="Select Lab" required class="mb-0" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label required" for="nama_alat">Equipment Name</label>
                                <div class="col-sm-10">
                                    <x-tabler.form-input name="nama_alat" value="{{ $inventory->nama_alat }}" placeholder="e.g., Laptop, Microscope, etc." required class="mb-0" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label required" for="jenis_alat">Type</label>
                                <div class="col-sm-10">
                                    <x-tabler.form-input name="jenis_alat" value="{{ $inventory->jenis_alat }}" placeholder="e.g., Electronic, Chemical, Equipment" required class="mb-0" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label required" for="kondisi_terakhir">Condition</label>
                                <div class="col-sm-10">
                                    <x-tabler.form-select name="kondisi_terakhir" :options="['Baik' => 'Good', 'Rusak Ringan' => 'Minor Damage', 'Rusak Berat' => 'Major Damage', 'Tidak Dapat Digunakan' => 'Cannot Be Used']" selected="{{ old('kondisi_terakhir', $inventory->kondisi_terakhir) }}" placeholder="Select Condition" required class="mb-0" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label required" for="tanggal_pengecekan">Last Check Date</label>
                                <div class="col-sm-10">
                                    <x-tabler.form-input type="date" name="tanggal_pengecekan" value="{{ $inventory->tanggal_pengecekan ? $inventory->tanggal_pengecekan->format('Y-m-d') : '' }}" required class="mb-0" />
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-sm-10 offset-sm-2">
                                    <x-tabler.button type="submit" text="Update Inventory" />
                                    <x-tabler.button type="cancel" :href="route('lab.inventaris.index')" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif


