<x-tabler.form-modal
    title="Tambah Sesi Ujian"
    route="{{ route('pmb.sesi-ujian.store') }}"
    method="POST"
    submitText="Simpan Sesi"
    data-redirect="true"
>
    <div class="mb-3">
        <label class="form-label required">Periode</label>
        <select name="periode_id" class="form-select" required>
            @foreach($periode as $p)
                <option value="{{ $p->encrypted_periode_id }}">{{ $p->nama_periode }}</option>
            @endforeach
        </select>
    </div>
    <x-tabler.form-input name="nama_sesi" label="Nama Sesi" placeholder="Contoh: Sesi 1 - Gelombang 1" required="true" />
    
    <div class="row">
        <div class="col-6">
            <x-tabler.form-input type="datetime-local" name="waktu_mulai" label="Waktu Mulai" required="true" />
        </div>
        <div class="col-6">
            <x-tabler.form-input type="datetime-local" name="waktu_selesai" label="Waktu Selesai" required="true" />
        </div>
    </div>

    <x-tabler.form-input name="lokasi" label="Lokasi" placeholder="Contoh: Lab Komputer 1" required="true" />
    <x-tabler.form-input type="number" name="kuota" label="Kuota Peserta" value="30" required="true" />
</x-tabler.form-modal>
