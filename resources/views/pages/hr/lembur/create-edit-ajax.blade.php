@php
    $isEdit = $lembur->exists;
    $title  = $isEdit ? 'Edit Lembur' : 'Tambah Lembur';
    $route  = $isEdit 
        ? route('hr.lembur.update', $lembur->encrypted_lembur_id) 
        : route('hr.lembur.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$isEdit ? 'Update' : 'Simpan'"
>
    <div class="row">
        {{-- Pengusul --}}
        <div class="col-md-12 mb-3">
            <x-tabler.form-select name="pengusul_id" label="Pengusul" required="true">
                <option value="">Pilih Pengusul</option>
                @foreach($pegawais as $pegawai)
                    <option value="{{ $pegawai->pegawai_id }}" 
                        {{ (old('pengusul_id', $lembur->pengusul_id) == $pegawai->pegawai_id) ? 'selected' : '' }}>
                        {{ $pegawai->latestDataDiri?->nama }}
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>

        <div class="col-md-12 mb-3">
            <x-tabler.form-input name="judul" label="Judul Lembur" :value="old('judul', $lembur->judul)" required />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-textarea name="uraian_pekerjaan" label="Uraian Pekerjaan" rows="3" :value="old('uraian_pekerjaan', $lembur->uraian_pekerjaan)" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-textarea name="alasan" label="Alasan Lembur" rows="2" :value="old('alasan', $lembur->alasan)" />
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-input type="date" name="tgl_pelaksanaan" label="Tanggal Pelaksanaan" :value="old('tgl_pelaksanaan', $lembur->tgl_pelaksanaan?->format('Y-m-d'))" required />
        </div>
        <div class="col-md-4 mb-3">
            @php
                $jamMulai = $lembur->jam_mulai;
                if ($jamMulai instanceof \DateTimeInterface) {
                    $jamMulai = $jamMulai->format('H:i');
                }
            @endphp
            <x-tabler.form-input type="time" name="jam_mulai" label="Jam Mulai" :value="old('jam_mulai', $jamMulai)" required />
        </div>
        <div class="col-md-4 mb-3">
            @php
                $jamSelesai = $lembur->jam_selesai;
                if ($jamSelesai instanceof \DateTimeInterface) {
                    $jamSelesai = $jamSelesai->format('H:i');
                }
            @endphp
            <x-tabler.form-input type="time" name="jam_selesai" label="Jam Selesai" :value="old('jam_selesai', $jamSelesai)" required />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-select name="pegawai_ids" id="pegawai_ids" label="Pegawai yang Lembur" :multiple="true" required="true" type="select2" data-placeholder="Cari Pegawai...">
                @foreach($pegawais as $pegawai)
                    <option value="{{ $pegawai->pegawai_id }}" 
                        {{ (collect(old('pegawai_ids', $lembur->pegawais->pluck('pegawai_id')->toArray()))->contains($pegawai->pegawai_id)) ? 'selected' : '' }}>
                        {{ $pegawai->latestDataDiri?->inisial }} - {{ $pegawai->latestDataDiri?->nama }}
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>
    </div>
</x-tabler.form-modal>
