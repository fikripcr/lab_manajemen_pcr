<x-tabler.form-modal 
    :title="$survei->exists ? 'Edit Pengaturan Survei' : 'Buat Survei Baru'" 
    :route="$survei->exists ? route('survei.update', $survei->id) : route('survei.store')" 
    :method="$survei->exists ? 'PUT' : 'POST'" 
    :submitText="$survei->exists ? 'Simpan Perubahan' : 'Simpan & Lanjut ke Builder'" 
    :submitIcon="$survei->exists ? 'ti ti-device-floppy' : 'ti ti-arrow-right'" 
    :data-redirect="!$survei->exists"
>
    <x-tabler.form-input name="judul" label="Judul Survei" required="true" :value="old('judul', $survei->judul)" placeholder="Contoh: Evaluasi Dosen 2024" />
    <x-tabler.form-input name="periode" label="Periode" :value="old('periode', $survei->periode)" placeholder="Contoh: Ganjil 2024/2025" />
    <x-tabler.form-textarea name="deskripsi" label="Deskripsi" rows="3">{{ old('deskripsi', $survei->deskripsi) }}</x-tabler.form-textarea>
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-select name="target_role" label="Target Pengguna" required="true"
                :options="['Mahasiswa' => 'Mahasiswa', 'Dosen' => 'Dosen', 'Tendik' => 'Tendik', 'Alumni' => 'Alumni', 'Umum' => 'Umum']"
                :selected="old('target_role', $survei->target_role)" 
                placeholder="Pilih Target" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-select name="is_aktif" label="Status"
                :options="['1' => 'Publish', '0' => 'Draft']"
                :selected="old('is_aktif', $survei->is_aktif ?? 0)" :placeholder="false" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <x-tabler.form-select name="mode" label="Mode Navigasi" required="true"
                :options="['Linear' => 'Linear (Per Halaman)', 'Bercabang' => 'Bercabang (Dinamis)']"
                :selected="old('mode', $survei->mode ?? 'Linear')" :placeholder="false" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tanggal_mulai" label="Tanggal Mulai" :value="old('tanggal_mulai', $survei->tanggal_mulai?->format('Y-m-d'))" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tanggal_selesai" label="Tanggal Selesai" :value="old('tanggal_selesai', $survei->tanggal_selesai?->format('Y-m-d'))" />
        </div>
    </div>

    <input type="hidden" name="wajib_login" value="0">
    <x-tabler.form-checkbox name="wajib_login" label="Wajib Login (User Kampus)" :switch="true" :checked="old('wajib_login', $survei->exists ? $survei->wajib_login : 1)" />
    
    <input type="hidden" name="bisa_isi_ulang" value="0">
    <x-tabler.form-checkbox name="bisa_isi_ulang" label="Bisa Isi Ulang" :switch="true" :checked="old('bisa_isi_ulang', $survei->exists ? $survei->bisa_isi_ulang : 0)" />
</x-tabler.form-modal>
