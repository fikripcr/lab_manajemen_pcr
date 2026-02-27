<x-tabler.form-modal
    id_form="{{ $labInventaris->exists ? 'editLabInventarisForm' : 'createLabInventarisForm' }}"
    title="{{ $labInventaris->exists ? 'Update Inventaris Lab' : 'Tambah Inventaris ke Lab' }}"
    route="{{ $labInventaris->exists ? route('lab.labs.inventaris.update', [$lab->encrypted_lab_id, $labInventaris->encrypted_id]) : route('lab.labs.inventaris.store', $lab->encrypted_lab_id) }}"
    method="{{ $labInventaris->exists ? 'PUT' : 'POST' }}"
>
    <div class="mb-3">
        @if($labInventaris->exists)
            <x-tabler.form-input 
                label="Alat" 
                value="{{ $labInventaris->inventaris->nama_alat }}" 
                disabled 
            />
            <input type="hidden" name="inventaris_id" value="{{ $labInventaris->inventaris_id }}">
        @else
            <x-tabler.form-select 
                name="inventaris_id" 
                label="Pilih Alat" 
                :options="[]" 
                placeholder="Cari alat..." 
                class="ajax-select"
                data-url="{{ route('lab.labs.inventaris.get-inventaris', $lab->encrypted_lab_id) }}"
                required 
            />
        @endif
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            name="no_series" 
            label="Nomor Seri" 
            value="{{ old('no_series', $labInventaris->no_series) }}"
            placeholder="Masukkan nomor seri alat..." 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            type="date" 
            name="tanggal_penempatan" 
            label="Tanggal Penempatan" 
            value="{{ old('tanggal_penempatan', $labInventaris->tanggal_penempatan ? $labInventaris->tanggal_penempatan->format('Y-m-d') : now()->format('Y-m-d')) }}"
            required 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-select 
            name="status" 
            label="Status" 
            :options="['active' => 'Active', 'moved' => 'Moved', 'inactive' => 'Inactive']" 
            selected="{{ old('status', $labInventaris->status) }}"
            required 
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-textarea 
            name="keterangan" 
            label="Keterangan" 
            value="{{ old('keterangan', $labInventaris->keterangan) }}"
            placeholder="Tambahkan catatan jika perlu..." 
        />
    </div>
</x-tabler.form-modal>
