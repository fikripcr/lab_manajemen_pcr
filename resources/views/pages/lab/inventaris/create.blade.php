@if(request()->ajax())
    <x-tabler.form-modal
        title="Create New Inventory"
        route="{{ route('lab.inventaris.store') }}"
        method="POST"
        submitText="Create Inventory"
    >
        <x-tabler.flash-message />

        <x-tabler.form-select name="lab_id" label="Lab" :options="$labs->pluck('name', 'lab_id')->toArray()" selected="{{ old('lab_id') }}" placeholder="Select Lab" required class="mb-3" />

        <x-tabler.form-input name="nama_alat" label="Equipment Name" placeholder="e.g., Laptop, Microscope, etc." required />

        <x-tabler.form-input name="jenis_alat" label="Type" placeholder="e.g., Electronic, Chemical, Equipment" required />

        <x-tabler.form-select name="kondisi_terakhir" label="Condition" :options="['Baik' => 'Good', 'Rusak Ringan' => 'Minor Damage', 'Rusak Berat' => 'Major Damage', 'Tidak Dapat Digunakan' => 'Cannot Be Used']" selected="{{ old('kondisi_terakhir') }}" placeholder="Select Condition" required class="mb-3" />

        <x-tabler.form-input type="date" name="tanggal_pengecekan" label="Last Check Date" value="{{ date('Y-m-d') }}" required />
    </x-tabler.form-modal>
@else
    @extends('layouts.tabler.app')

    @section('header')
        <x-tabler.page-header title="Create New Inventory" pretitle="Inventory">
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

                        <form action="{{ route('lab.inventaris.store') }}" method="POST" class="ajax-form">
                            @csrf

                            <x-tabler.form-select name="lab_id" label="Lab" :options="$labs->pluck('name', 'lab_id')->toArray()" selected="{{ old('lab_id') }}" placeholder="Select Lab" required class="mb-3" />

                            <x-tabler.form-input name="nama_alat" label="Equipment Name" placeholder="e.g., Laptop, Microscope, etc." required />

                            <x-tabler.form-input name="jenis_alat" label="Type" placeholder="e.g., Electronic, Chemical, Equipment" required />

                            <x-tabler.form-select name="kondisi_terakhir" label="Condition" :options="['Baik' => 'Good', 'Rusak Ringan' => 'Minor Damage', 'Rusak Berat' => 'Major Damage', 'Tidak Dapat Digunakan' => 'Cannot Be Used']" selected="{{ old('kondisi_terakhir') }}" placeholder="Select Condition" required class="mb-3" />

                            <x-tabler.form-input type="date" name="tanggal_pengecekan" label="Last Check Date" value="{{ date('Y-m-d') }}" required />

                            <div class="mt-4">
                                <x-tabler.button type="submit" text="Create Inventory" />
                                <x-tabler.button type="cancel" :href="route('lab.inventaris.index')" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif


