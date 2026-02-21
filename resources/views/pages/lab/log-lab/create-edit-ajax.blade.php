<x-tabler.form-modal
    id_form="{{ $log->exists ? 'editLogLabForm' : 'createLogLabForm' }}"
    title="{{ $log->exists ? 'Update Log Penggunaan Lab' : 'Isi Log Penggunaan Lab' }}"
    route="{{ $log->exists ? route('lab.log-lab.update', $log->encrypted_log_penggunaan_lab_id) : route('lab.log-lab.store') }}"
    method="{{ $log->exists ? 'PUT' : 'POST' }}"
>
    @if(!$log->exists)
        <x-tabler.form-select 
            name="kegiatan_id" 
            label="Kegiatan (Opsional)" 
            :options="$activeKegiatans->mapWithKeys(fn($k) => [encryptId($k->kegiatan_id) => $k->nama_kegiatan . ' (' . $k->jam_mulai->format('H:i') . ' - ' . $k->jam_selesai->format('H:i') . ')'])->toArray()" 
            placeholder="-- Pilih Kegiatan Hari Ini --" 
            class="select2-offline mb-3" 
            help="Jika anda mengikuti kegiatan, pilih di sini. Lab akan terpilih otomatis." 
        />

        <div class="hr-text my-2 text-muted">ATAU</div>

        <x-tabler.form-select 
            name="lab_id" 
            label="Lab (Jika tidak ada kegiatan spesifik)" 
            :options="$labs->mapWithKeys(fn($lab) => [encryptId($lab->lab_id) => $lab->name])->toArray()" 
            placeholder="-- Pilih Lab --" 
            class="select2-offline mb-3" 
        />

        <x-tabler.form-input 
            name="nama_peserta" 
            label="Nama Lengkap" 
            placeholder="Masukkan nama lengkap anda..." 
            required 
        />

        <div class="row mt-3">
            <div class="col-md-6">
                <x-tabler.form-input 
                    name="npm_peserta" 
                    label="NPM / NIK (Opsional)" 
                    placeholder="Nomor Induk" 
                />
            </div>
            <div class="col-md-6">
                <x-tabler.form-input 
                    type="email" 
                    name="email_peserta" 
                    label="Email (Opsional)" 
                    placeholder="Email aktif" 
                />
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <x-tabler.form-input 
                    type="number" 
                    name="nomor_pc" 
                    label="Nomor PC (Opsional)" 
                    placeholder="Contoh: 10" 
                />
            </div>
            <div class="col-md-6">
                <x-tabler.form-select 
                    name="kondisi" 
                    label="Kondisi PC / Alat" 
                    :options="['Baik' => 'Baik', 'Rusak' => 'Rusak / Bermasalah']" 
                    placeholder="Pilih Kondisi" 
                    required 
                />
            </div>
        </div>

        <div class="mt-3">
            <x-tabler.form-textarea 
                name="catatan_umum" 
                label="Catatan Tambahan (Opsional)" 
                rows="2" 
            />
        </div>
    @else
        {{-- View/Edit for Admin --}}
        <div class="mb-3">
            <x-tabler.form-input 
                name="nama_peserta" 
                label="Nama Peserta" 
                value="{{ $log->nama_peserta }}"
                required
            />
        </div>
        <div class="mb-3">
            <x-tabler.form-select 
                name="kondisi" 
                label="Kondisi" 
                :options="['Baik' => 'Baik', 'Rusak' => 'Rusak']" 
                selected="{{ $log->kondisi }}"
                required
            />
        </div>
        <x-tabler.form-textarea 
            name="catatan_umum" 
            label="Catatan" 
            rows="3"
        >{{ old('catatan_umum', $log->catatan_umum) }}</x-tabler.form-textarea>
    @endif
</x-tabler.form-modal>
