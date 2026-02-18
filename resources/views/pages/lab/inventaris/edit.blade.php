@if(request()->ajax())
    <x-tabler.form-modal
        title="Edit Inventory"
        route="{{ route('lab.inventaris.update', $inventory) }}"
        method="PUT"
        submitText="Update Inventory"
    >
        <x-tabler.flash-message />

        <x-tabler.form-select name="lab_id" label="Lab" :options="$labs->pluck('name', 'lab_id')->toArray()" selected="{{ old('lab_id', $inventory->lab_id) }}" placeholder="Select Lab" required class="mb-3" />

        <x-tabler.form-input name="nama_alat" label="Equipment Name" value="{{ $inventory->nama_alat }}" placeholder="e.g., Laptop, Microscope, etc." required />

        <x-tabler.form-input name="jenis_alat" label="Type" value="{{ $inventory->jenis_alat }}" placeholder="e.g., Electronic, Chemical, Equipment" required />

        <x-tabler.form-select name="kondisi_terakhir" label="Condition" :options="['Baik' => 'Good', 'Rusak Ringan' => 'Minor Damage', 'Rusak Berat' => 'Major Damage', 'Tidak Dapat Digunakan' => 'Cannot Be Used']" selected="{{ old('kondisi_terakhir', $inventory->kondisi_terakhir) }}" placeholder="Select Condition" required class="mb-3" />

        <x-tabler.form-input type="date" name="tanggal_pengecekan" label="Last Check Date" value="{{ $inventory->tanggal_pengecekan ? $inventory->tanggal_pengecekan->format('Y-m-d') : '' }}" required />
    </x-tabler.form-modal>
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

                            <x-tabler.form-select name="lab_id" label="Lab" :options="$labs->pluck('name', 'lab_id')->toArray()" selected="{{ old('lab_id', $inventory->lab_id) }}" placeholder="Select Lab" required class="mb-3" />

                            <x-tabler.form-input name="nama_alat" label="Equipment Name" value="{{ $inventory->nama_alat }}" placeholder="e.g., Laptop, Microscope, etc." required />

                            <x-tabler.form-input name="jenis_alat" label="Type" value="{{ $inventory->jenis_alat }}" placeholder="e.g., Electronic, Chemical, Equipment" required />

                            <x-tabler.form-select name="kondisi_terakhir" label="Condition" :options="['Baik' => 'Good', 'Rusak Ringan' => 'Minor Damage', 'Rusak Berat' => 'Major Damage', 'Tidak Dapat Digunakan' => 'Cannot Be Used']" selected="{{ old('kondisi_terakhir', $inventory->kondisi_terakhir) }}" placeholder="Select Condition" required class="mb-3" />

                            <x-tabler.form-input type="date" name="tanggal_pengecekan" label="Last Check Date" value="{{ $inventory->tanggal_pengecekan ? $inventory->tanggal_pengecekan->format('Y-m-d') : '' }}" required />

                            <div class="mt-4">
                                <x-tabler.button type="submit" text="Update Inventory" />
                                <x-tabler.button type="cancel" :href="route('lab.inventaris.index')" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif


