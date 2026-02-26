<x-tabler.form-modal
    id_form="{{ $softwareRequest->exists ? 'editSoftwareRequestForm' : 'createSoftwareRequestForm' }}"
    title="{{ $softwareRequest->exists ? 'Edit Permintaan Software' : 'Buat Request Software' }}"
    route="{{ $softwareRequest->exists ? route('lab.software-requests.update', $softwareRequest->encrypted_request_software_id) : route('lab.software-requests.store') }}"
    method="{{ $softwareRequest->exists ? 'PUT' : 'POST' }}"
>
    @if(isset($error))
        <div class="alert alert-warning">
            <div class="d-flex">
                <div>
                    <i class="ti ti-alert-triangle me-2 fs-2"></i>
                </div>
                <div>
                    {{ $error }}
                </div>
            </div>
        </div>
    @endif

    @if($activePeriod)
        <div class="alert alert-info py-2 mb-3">
            <strong>Periode Aktif:</strong> {{ $activePeriod->nama_periode }} 
            ({{ formatTanggalIndo($activePeriod->start_date) }} - {{ formatTanggalIndo($activePeriod->end_date) }})
        </div>
        <input type="hidden" name="periodsoftreq_id" value="{{ $activePeriod->periodsoftreq_id }}">
    @endif
    
    <div class="mb-3">
        <x-tabler.form-select 
            name="mata_kuliah_ids[]" 
            label="Mata Kuliah" 
            :options="$mataKuliahs->pluck('nama_mk', 'mata_kuliah_id')->toArray()" 
            :selected="old('mata_kuliah_ids', $softwareRequest->exists ? $softwareRequest->mataKuliahs->pluck('mata_kuliah_id')->toArray() : [])"
            multiple 
            required
            placeholder="Pilih Mata Kuliah"
            class="select2"
            help="Pilih satu atau lebih mata kuliah yang membutuhkan software ini."
        />
    </div>

    <x-tabler.form-input 
        name="nama_software" 
        label="Nama Software" 
        value="{{ old('nama_software', $softwareRequest->nama_software) }}"
        placeholder="Misal: Visual Studio Code, MATLAB 2024" 
        required 
        :disabled="!$activePeriod && !$softwareRequest->exists" 
    />

    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input 
                name="versi" 
                label="Versi (Opsional)" 
                value="{{ old('versi', $softwareRequest->versi) }}"
                placeholder="Contoh: v1.8.0" 
                :disabled="!$activePeriod && !$softwareRequest->exists" 
            />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input 
                type="url" 
                name="url_download" 
                label="URL Download (Opsional)" 
                value="{{ old('url_download', $softwareRequest->url_download) }}"
                placeholder="https://..." 
                :disabled="!$activePeriod && !$softwareRequest->exists" 
            />
        </div>
    </div>

    <x-tabler.form-textarea 
        name="deskripsi" 
        id="{{ $softwareRequest->exists ? 'deskripsi_edit_modal' : 'deskripsi_modal' }}" 
        label="Keterangan / Deskripsi" 
        required="true" 
        height="200"
        :value="old('deskripsi', $softwareRequest->deskripsi)"
    />

    @push('js')
    <script>
        $(document).ready(function() {
            window.loadSelect2();
            if (window.loadHugeRTE) {
                window.loadHugeRTE('#{{ $softwareRequest->exists ? "deskripsi_edit_modal" : "deskripsi_modal" }}', { height: 200 });
            }
        });
    </script>
    @endpush
</x-tabler.form-modal>
