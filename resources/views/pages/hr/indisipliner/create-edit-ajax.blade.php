@php
    $isEdit = $indisipliner->exists;
    $title  = $isEdit ? 'Edit Indisipliner' : 'Tambah Indisipliner';
    $route  = $isEdit 
        ? route('hr.indisipliner.update', $indisipliner->encrypted_indisipliner_id) 
        : route('hr.indisipliner.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    enctype="multipart/form-data"
>
    <div class="row">
        <div class="col-md-8 mb-3">
            <x-tabler.form-select name="jenisindisipliner_id" label="Jenis Indisipliner" required="true">
                <option value="">Pilih Jenis Indisipliner</option>
                @foreach($jenisIndisipliner as $jenis)
                    <option value="{{ $jenis->jenisindisipliner_id }}" {{ $indisipliner->jenisindisipliner_id == $jenis->jenisindisipliner_id ? 'selected' : '' }}>
                        {{ $jenis->jenis_indisipliner }}
                    </option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="col-md-4 mb-3">
            <x-tabler.form-input name="tgl_indisipliner" label="Tanggal" type="date" :value="$indisipliner->tgl_indisipliner ? $indisipliner->tgl_indisipliner->format('Y-m-d') : ''" required="true" />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-select 
                name="pegawai_id[]" 
                label="Pegawai" 
                class="select2-pegawai" 
                multiple="true" 
                required="true"
                data-placeholder="Cari Nama / NIP Pegawai"
                data-dropdown-parent="#modalAction"
            >
                @if($isEdit)
                    @foreach($indisipliner->indisiplinerPegawai as $ip)
                        <option value="{{ $ip->pegawai_id }}" selected>
                            {{ $ip->pegawai->nama }} ({{ $ip->pegawai->nip ?? 'No NIP' }})
                        </option>
                    @endforeach
                @endif
            </x-tabler.form-select>
            <small class="text-muted">Dapat memilih lebih dari satu pegawai.</small>
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-input type="file" name="bukti" label="Bukti Pemberian (Optional)" help="File format: jpg, png, pdf. Max 2MB." />
            @if($isEdit && $indisipliner->bukti)
                <div class="mt-1">
                    <a href="{{ asset('storage/' . $indisipliner->bukti) }}" target="_blank" class="btn btn-sm btn-info">
                        <i class="ti ti-file-search me-1"></i> Lihat Bukti Saat Ini
                    </a>
                </div>
            @endif
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-textarea name="keterangan" label="Keterangan / Uraian" rows="3" :value="$indisipliner->keterangan" />
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            window.loadSelect2();
            $('.select2-pegawai').select2({
                dropdownParent: $('#modalAction'),
                ajax: {
                    url: '{{ route("hr.pegawai.select2") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });
        });
    </script>
    @endpush
</x-tabler.form-modal>
