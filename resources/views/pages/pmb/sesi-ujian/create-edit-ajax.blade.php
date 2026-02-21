@php
    $isEdit = isset($sesi) && $sesi->exists;
@endphp

<x-tabler.form-modal
    title="{{ $isEdit ? 'Edit Sesi Ujian' : 'Tambah Sesi Ujian' }}"
    route="{{ $isEdit ? route('pmb.sesi-ujian.update', $sesi->encrypted_sesiujian_id) : route('pmb.sesi-ujian.store') }}"
    method="{{ $isEdit ? 'PUT' : 'POST' }}"
    submitText="{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Sesi' }}"
    data-redirect="true"
>
    <div class="mb-3">
        <label class="form-label required">Periode</label>
        <select name="periode_id" class="form-select" required>
            <option value="">-- Pilih Periode --</option>
            @foreach($periode as $p)
                <option value="{{ $p->encrypted_periode_id }}" 
                    {{ ($isEdit && $sesi->periode_id == $p->periode_id) ? 'selected' : '' }}>
                    {{ $p->nama_periode }}
                </option>
            @endforeach
        </select>
    </div>

    <x-tabler.form-input name="nama_sesi" label="Nama Sesi" 
        :value="$isEdit ? $sesi->nama_sesi : ''" 
        placeholder="Contoh: Sesi 1 - Gelombang 1" required="true" />
    
    <div class="row">
        <div class="col-6">
            <x-tabler.form-input type="datetime-local" name="waktu_mulai" label="Waktu Mulai" 
                :value="$isEdit ? $sesi->waktu_mulai->format('Y-m-d\TH:i') : ''" required="true" />
        </div>
        <div class="col-6">
            <x-tabler.form-input type="datetime-local" name="waktu_selesai" label="Waktu Selesai" 
                :value="$isEdit ? $sesi->waktu_selesai->format('Y-m-d\TH:i') : ''" required="true" />
        </div>
    </div>

    <x-tabler.form-input name="lokasi" label="Lokasi" 
        :value="$isEdit ? $sesi->lokasi : ''" 
        placeholder="Contoh: Lab Komputer 1" required="true" />
        
    <x-tabler.form-input type="number" name="kuota" label="Kuota Peserta" 
        :value="$isEdit ? $sesi->kuota : '30'" required="true" />
</x-tabler.form-modal>
