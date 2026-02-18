<x-tabler.form-modal
    title="Buat Survei Baru"
    route="{{ route('survei.store') }}"
    method="POST"
    submitText="Simpan & Lanjut ke Builder"
    submitIcon="ti ti-arrow-right"
    data-redirect="true"
>
    <x-tabler.form-input name="judul" label="Judul Survei" required="true" placeholder="Contoh: Evaluasi Dosen 2024" />
    <x-tabler.form-input name="periode" label="Periode" placeholder="Contoh: Ganjil 2024/2025" />
    <x-tabler.form-textarea name="deskripsi" label="Deskripsi" rows="3" />
    
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-select name="target_role" label="Target Role" required="true"
                :options="['Mahasiswa' => 'Mahasiswa', 'Dosen' => 'Dosen', 'Tendik' => 'Tendik', 'Alumni' => 'Alumni', 'Umum' => 'Umum']"
                placeholder="Pilih Target" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-select name="is_aktif" label="Status"
                :options="['1' => 'Publish', '0' => 'Draft']"
                selected="0" :placeholder="false" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <x-tabler.form-select name="mode" label="Mode Navigasi" required="true"
                :options="['Linear' => 'Linear (Per Halaman)', 'Bercabang' => 'Bercabang (Dinamis)']"
                selected="Linear" :placeholder="false" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tanggal_mulai" label="Tanggal Mulai" />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tanggal_selesai" label="Tanggal Selesai" />
        </div>
    </div>

    <input type="hidden" name="wajib_login" value="0">
    <x-tabler.form-checkbox name="wajib_login" label="Wajib Login (User Kampus)" :switch="true" :checked="true" />
    
    <input type="hidden" name="bisa_isi_ulang" value="0">
</x-tabler.form-modal>
