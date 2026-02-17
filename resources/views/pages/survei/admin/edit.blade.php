<form action="{{ route('survei.update', $survei->id) }}" method="POST" class="ajax-form" data-redirect="true">
    @csrf
    @method('PUT')
    <div class="modal-header">
        <h5 class="modal-title">Edit Pengaturan Survei</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <x-tabler.form-input name="judul" label="Judul Survei" required="true" :value="$survei->judul" />
        <x-tabler.form-textarea name="deskripsi" label="Deskripsi" rows="3" :value="$survei->deskripsi" />
        
        <div class="row">
            <div class="col-md-6">
                <x-tabler.form-select name="target_role" label="Target Role" required="true"
                    :options="['Mahasiswa' => 'Mahasiswa', 'Dosen' => 'Dosen', 'Tendik' => 'Tendik', 'Alumni' => 'Alumni', 'Umum' => 'Umum']"
                    :selected="$survei->target_role" placeholder="Pilih Target" />
            </div>
            <div class="col-md-6">
                <x-tabler.form-select name="is_aktif" label="Status"
                    :options="['1' => 'Aktif', '0' => 'Draft / Tidak Aktif']"
                    :selected="$survei->is_aktif ? '1' : '0'" :placeholder="false" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <x-tabler.form-select name="mode" label="Mode Navigasi" required="true"
                    :options="['Linear' => 'Linear (Per Halaman)', 'Bercabang' => 'Bercabang (Dinamis)']"
                    :selected="$survei->mode" :placeholder="false" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <x-tabler.form-input type="date" name="tanggal_mulai" label="Tanggal Mulai" 
                    :value="$survei->tanggal_mulai ? $survei->tanggal_mulai->format('Y-m-d') : ''" />
            </div>
            <div class="col-md-6">
                <x-tabler.form-input type="date" name="tanggal_selesai" label="Tanggal Selesai" 
                    :value="$survei->tanggal_selesai ? $survei->tanggal_selesai->format('Y-m-d') : ''" />
            </div>
        </div>

        <input type="hidden" name="wajib_login" value="0">
        <x-tabler.form-checkbox name="wajib_login" label="Wajib Login (User Kampus)" :switch="true" :checked="$survei->wajib_login" />
        
        <input type="hidden" name="bisa_isi_ulang" value="0">
        <x-tabler.form-checkbox name="bisa_isi_ulang" label="Boleh Mengisi Berulang Kali?" :switch="true" :checked="$survei->bisa_isi_ulang" />
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
        <x-tabler.button type="submit" class="btn-primary ms-auto" icon="ti ti-device-floppy" text="Simpan Perubahan" />
    </div>
</form>
