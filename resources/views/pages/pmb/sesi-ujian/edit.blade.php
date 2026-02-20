<x-tabler.form-modal
    title="Edit Sesi Ujian"
    route="{{ route('pmb.sesi-ujian.update', $sesiUjian->encrypted_sesiujian_id) }}"
    method="PUT"
    submitText="Simpan Perubahan"
    data-redirect="true"
>
    <div class="mb-3">
        <label class="form-label required">Periode</label>
        <select name="periode_id" class="form-select" required>
            @foreach($periode as $p)
                <option value="{{ $p->periode_id }}" {{ $sesiUjian->periode_id == $p->periode_id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>
            @endforeach
        </select>
    </div>
    <x-tabler.form-input name="nama_sesi" label="Nama Sesi" :value="$sesiUjian->nama_sesi" required="true" />
    
    <div class="row">
        <div class="col-6">
            <x-tabler.form-input type="datetime-local" name="waktu_mulai" label="Waktu Mulai" :value="$sesiUjian->waktu_mulai->format('Y-m-d\TH:i')" required="true" />
        </div>
        <div class="col-6">
            <x-tabler.form-input type="datetime-local" name="waktu_selesai" label="Waktu Selesai" :value="$sesiUjian->waktu_selesai->format('Y-m-d\TH:i')" required="true" />
        </div>
    </div>

    <x-tabler.form-input name="lokasi" label="Lokasi" :value="$sesiUjian->lokasi" required="true" />
    <x-tabler.form-input type="number" name="kuota" label="Kuota Peserta" :value="$sesiUjian->kuota" required="true" />
</x-tabler.form-modal>
