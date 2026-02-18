<x-tabler.form-modal
    title="Edit Paket Ujian"
    route="{{ route('cbt.paket.update', $paket->hashid) }}"
    method="PUT"
    data-redirect="true"
    submitText="Update"
>
    <x-tabler.form-input name="nama_paket" label="Nama Paket" value="{{ $paket->nama_paket }}" required="true" />
    <x-tabler.form-select name="tipe_paket" label="Tipe Paket" required="true" 
        :options="['PMB' => 'PMB (Penerimaan Mahasiswa Baru)', 'Akademik' => 'Akademik (UTS/UAS)']" 
        :selected="$paket->tipe_paket" />

    <x-tabler.form-input name="total_durasi_menit" label="Durasi (Menit)" type="number" value="{{ $paket->total_durasi_menit }}" required="true" />
    <x-tabler.form-input name="kk_nilai_minimal" label="Passing Grade (Minimal Nilai)" type="number" value="{{ $paket->kk_nilai_minimal }}" />
    
    <div class="mt-3">
        <x-tabler.form-checkbox name="is_acak_soal" label="Acak Soal" value="1" :checked="$paket->is_acak_soal" :switch="true" />
        <x-tabler.form-checkbox name="is_acak_opsi" label="Acak Opsi Jawaban" value="1" :checked="$paket->is_acak_opsi" :switch="true" />
    </div>
</x-tabler.form-modal>
