<x-tabler.form-modal
    title="Tambah Paket Ujian"
    route="{{ route('cbt.paket.store') }}"
    method="POST"
    data-redirect="true"
    submitText="Simpan"
>
    <x-tabler.form-input name="nama_paket" label="Nama Paket" placeholder="Contoh: Paket PMB Gel 1 2024" required="true" />
    <x-tabler.form-select name="tipe_paket" label="Tipe Paket" required="true">
        <option value="PMB">PMB (Penerimaan Mahasiswa Baru)</option>
        <option value="Akademik">Akademik (UTS/UAS)</option>
    </x-tabler.form-select>
    <x-tabler.form-input name="total_durasi_menit" label="Durasi (Menit)" type="number" value="60" required="true" />
    <x-tabler.form-input name="kk_nilai_minimal" label="Passing Grade (Minimal Nilai)" type="number" value="0" />
    
    <div class="mt-3">
        <x-tabler.form-checkbox name="is_acak_soal" label="Acak Soal" value="1" checked :switch="true" />
        <x-tabler.form-checkbox name="is_acak_opsi" label="Acak Opsi Jawaban" value="1" checked :switch="true" />
    </div>
</x-tabler.form-modal>
