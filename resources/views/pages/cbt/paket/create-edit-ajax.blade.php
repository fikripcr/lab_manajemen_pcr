@php
    $item = $paket ?? new \stdClass();
    $method = isset($paket) ? 'PUT' : 'POST';
    $route = isset($paket) ? route('cbt.paket.update', $paket->hashid) : route('cbt.paket.store');
    $title = isset($paket) ? 'Edit Paket Ujian' : 'Tambah Paket Ujian';
    $submitText = isset($paket) ? 'Update' : 'Simpan';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    data-redirect="true"
    :submitText="$submitText"
>
    <x-tabler.form-input 
        name="nama_paket" 
        label="Nama Paket" 
        placeholder="Contoh: Paket PMB Gel 1 2024" 
        :value="old('nama_paket', $item->nama_paket ?? '')"
        required="true" 
    />
    <x-tabler.form-select 
        name="tipe_paket" 
        label="Tipe Paket" 
        required="true" 
        :options="['PMB' => 'PMB (Penerimaan Mahasiswa Baru)', 'Akademik' => 'Akademik (UTS/UAS)']" 
        :selected="old('tipe_paket', $item->tipe_paket ?? '')" 
    />

    <x-tabler.form-input 
        name="total_durasi_menit" 
        label="Durasi (Menit)" 
        type="number" 
        :value="old('total_durasi_menit', $item->total_durasi_menit ?? 60)" 
        required="true" 
    />
    <x-tabler.form-input 
        name="kk_nilai_minimal" 
        label="Passing Grade (Minimal Nilai)" 
        type="number" 
        :value="old('kk_nilai_minimal', $item->kk_nilai_minimal ?? 0)" 
    />
    
    <div class="mt-3">
        <x-tabler.form-checkbox 
            name="is_acak_soal" 
            label="Acak Soal" 
            value="1" 
            :checked="old('is_acak_soal', $item->is_acak_soal ?? true)" 
            :switch="true" 
        />
        <x-tabler.form-checkbox 
            name="is_acak_opsi" 
            label="Acak Opsi Jawaban" 
            value="1" 
            :checked="old('is_acak_opsi', $item->is_acak_opsi ?? true)" 
            :switch="true" 
        />
    </div>
</x-tabler.form-modal>
