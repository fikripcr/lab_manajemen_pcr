@php
    $isEdit = false;
    $title = '';
    $route = '';
    $method = 'POST';
    $mode = $mode ?? null;

    // TYPE: 'dokumen', 'poin', 'indikator'
    if (!isset($type)) {
        $type = 'dokumen';
    }

    if ($type === 'dokumen') {
        $isEdit = isset($dokumen) && $dokumen->exists;
        $title = $isEdit ? ($mode === 'title' ? "Ubah Judul Dokumen" : ($mode === 'content' ? "Ubah Isi Dokumen" : "Ubah Dokumen")) : "Tambah Dokumen";
        $route = $isEdit ? route('pemutu.dokumen-spmi.update', ['type' => 'dokumen', 'id' => $dokumen->encrypted_dok_id]) : route('pemutu.dokumen-spmi.store', ['type' => 'dokumen']);
        $method = $isEdit ? 'PUT' : 'POST';
    } elseif ($type === 'poin') {
        $isEdit = isset($dokSub) && $dokSub->exists;
        $title = $isEdit ? ($mode === 'title' ? "Ubah Judul Poin" : ($mode === 'content' ? "Ubah Isi Poin" : "Ubah Poin")) : "Tambah Poin / Kegiatan";
        $route = $isEdit ? route('pemutu.dokumen-spmi.update', ['type' => 'poin', 'id' => $dokSub->encrypted_doksub_id]) : route('pemutu.dokumen-spmi.store', ['type' => 'poin']);
        $method = $isEdit ? 'PUT' : 'POST';
    } elseif ($type === 'indikator') {
        $isEdit = isset($indikator) && $indikator->exists;
        $title = $isEdit ? "Ubah Indikator" : "Tambah Indikator";
        $route = $isEdit ? route('pemutu.dokumen-spmi.update', ['type' => 'indikator', 'id' => $indikator->encrypted_indikator_id]) : route('pemutu.dokumen-spmi.store', ['type' => 'indikator']);
        $method = $isEdit ? 'PUT' : 'POST';
    }
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$isEdit ? 'Simpan Perubahan' : 'Simpan'"
    :submitIcon="$isEdit ? 'ti-device-floppy' : 'ti-plus'"
    :size="($mode === 'title' || (!$isEdit && $type === 'poin')) ? 'modal-lg' : 'modal-xl'"
>
    {{-- A. DOKUMEN FORM --}}
    @if($type === 'dokumen')
        <input type="hidden" name="jenis" value="{{ $isEdit ? $dokumen->jenis : (isset($fixedJenis) ? $fixedJenis : '') }}">
        <input type="hidden" name="periode" value="{{ $isEdit ? $dokumen->periode : ($currentPeriode ? $currentPeriode->periode : '') }}">
        <input type="hidden" name="parent_id" value="{{ $isEdit ? $dokumen->parent_id : (isset($parent) ? $parent->encrypted_dok_id : '') }}">

        @if(!$isEdit && $currentPeriode)
            <div class="mb-3">
                <label class="form-label">Tahun Siklus</label>
                <div class="form-control-plaintext fw-bold">
                    <span class="badge bg-blue-lt">{{ $currentPeriode->periode }} ({{ $currentPeriode->jenis_periode }})</span>
                </div>
            </div>
        @endif

        @if(!$isEdit)
            @if(isset($parentDokSub))
                <input type="hidden" name="parent_doksub_id" value="{{ $parentDokSub->encrypted_doksub_id }}">
            @endif
            @if(isset($fixedJenis))
                <div class="mb-3">
                    <label class="form-label">Jenis Dokumen</label>
                    <div class="form-control-plaintext fw-bold">{{ ucfirst($fixedJenis) }}</div>
                </div>
            @else
                <div class="mb-3">
                    <x-tabler.form-select
                        id="jenis"
                        name="jenis"
                        label="Jenis Dokumen"
                        required="true"
                        type="select2"
                        data-dropdown-parent="#modalAction"
                        :options="$allowedTypes ?? []"
                        :selected="null"
                        placeholder="Pilih Jenis..."
                    />
                </div>
            @endif
        @endif

        {{-- MODE FIELD: TITLE ONLY --}}
        @if(!$isEdit || $mode === 'title')
            <div class="mb-3">
                <x-tabler.form-input name="judul" id="judul" label="Judul Dokumen" :value="$isEdit ? $dokumen->judul : ''" placeholder="Contoh: Manual Mutu" required="true" />
            </div>

            <div class="mb-3">
                <x-tabler.form-input name="kode" id="kode" label="Kode Dokumen" :value="$isEdit ? $dokumen->kode : ''" placeholder="Contoh: MM-01" />
            </div>
        @else
            <input type="hidden" name="judul" value="{{ $dokumen->judul }}">
            <input type="hidden" name="kode" value="{{ $dokumen->kode }}">
        @endif

        {{-- MODE FIELD: CONTENT ONLY --}}
        @if($isEdit && ($mode === 'content' || !$mode))
            <div class="mb-3">
                <x-tabler.form-textarea name="isi" id="isi" height="400" rows="10" :value="$dokumen->isi" label="Konten Dokumen" />
            </div>
        @elseif($isEdit && $mode === 'title')
            <input type="hidden" name="isi" value="{{ $dokumen->isi }}">
        @endif

    {{-- B. POIN (DOKSUB) FORM --}}
    @elseif($type === 'poin')
        @php
            $jenisPoin = strtolower(trim($isEdit ? $dokSub->dokumen->jenis : $dokumen->jenis));
            $canProduceIndikator = in_array($jenisPoin, ['standar', 'formulir', 'manual_prosedur', 'renop']);
        @endphp

        @if(!$isEdit)
            <input type="hidden" name="dok_id" value="{{ $dokumen->encrypted_dok_id }}">
        @else
            <input type="hidden" name="dok_id" value="{{ $dokSub->dokumen->encrypted_dok_id }}">
            @if($mode !== 'title')
                <input type="hidden" name="is_hasilkan_indikator" value="{{ $dokSub->is_hasilkan_indikator ? '1' : '0' }}">
            @endif
        @endif

        @if(!$isEdit || $mode === 'title')
            <div class="row">
                <div class="col-sm-6">
                    <x-tabler.form-input name="kode" id="kode" label="Kode" :value="$isEdit ? $dokSub->kode : ''" placeholder="Contoh: S.01" />
                </div>
                @if($canProduceIndikator)
                <div class="col-sm-6">
                    <x-tabler.form-select
                        name="is_hasilkan_indikator"
                        label="Apakah poin ini menghasilkan indikator?"
                        :options="['1' => 'Ya, Menghasilkan Indikator', '0' => 'Tidak / Poin Biasa']"
                        :selected="$isEdit ? ($dokSub->is_hasilkan_indikator ? '1' : '0') : (($jenisPoin === 'renop' || $jenisPoin === 'standar') ? '1' : '0')"
                        required="true"
                        data-dropdown-parent="#modalAction"
                    />
                </div>
                @endif
            </div>

            <div class="mb-3 mt-3">
                <x-tabler.form-textarea
                    name="judul"
                    label="Judul"
                    id="judul"
                    :value="$isEdit ? $dokSub->judul : ''"
                    required="true"
                    rows="4"
                    placeholder="{{ $canProduceIndikator ? 'Contoh: Standar Kompetensi Lulusan' : 'Contoh: Misi 1' }}"
                />
            </div>
        @else
            <input type="hidden" name="judul" value="{{ $dokSub->judul }}">
            <input type="hidden" name="kode" value="{{ $dokSub->kode }}">
        @endif

        @if($isEdit && ($mode === 'content' || !$mode))
            <div class="mt-3">
                @if(!$canProduceIndikator || $jenisPoin === 'renop' || $isEdit)
                    <x-tabler.form-textarea
                        :type="$isEdit ? 'editor' : 'textarea'"
                        name="isi"
                        id="isi"
                        label="Isi / Keterangan"
                        :value="$isEdit ? $dokSub->isi : ''"
                        rows="12"
                        placeholder="Uraian rinci terkait poin / kegiatan ini..."
                        :height="$isEdit ? 350 : null"
                    />
                @else
                    <div class="empty bg-transparent h-100 d-flex flex-column justify-content-center border-dashed rounded" style="min-height: 200px">
                        <div class="empty-icon"><i class="ti ti-file-text"></i></div>
                        <p class="empty-title mb-1">Isi / Keterangan</p>
                        <p class="empty-subtitle text-muted">Isi dapat dilengkapi setelah data awal disimpan atau telah memiliki referensi yang mendukung.</p>
                    </div>
                @endif
            </div>
        @elseif($isEdit && $mode === 'title')
            <input type="hidden" name="isi" value="{{ $dokSub->isi }}">
        @endif

    {{-- C. INDIKATOR FORM --}}
    @elseif($type === 'indikator')
        @if(!$isEdit)
            <input type="hidden" name="parent_dok_id" value="{{ isset($parentDok) ? $parentDok->encrypted_dok_id : '' }}">
            @if(isset($parentDokSub))
                <input type="hidden" name="doksub_ids[]" value="{{ $parentDokSub->encrypted_doksub_id }}">
            @endif
        @endif

        <div class="row">
            <div class="col-md-8">
                <x-tabler.form-textarea name="indikator" label="Nama Indikator" rows="2" placeholder="Masukkan nama indikator..." required="true" :value="$isEdit ? $indikator->indikator : ''" />
            </div>
            <div class="col-md-4">
                <x-tabler.form-input name="no_indikator" label="Kode / No" type="text" :value="$isEdit ? $indikator->no_indikator : ''" placeholder="cth: IND.01" />
            </div>
        </div>

        <div class="row">
            @php
                $isRenopContext = request('is_renop_context') == 1 || (isset($parentDokSub) && strtolower(trim($parentDokSub->dokumen->jenis)) === 'renop');
            @endphp
            <div class="col-md-6 mb-3">
                @if($isRenopContext && !$isEdit)
                    <input type="hidden" name="type" value="renop">
                    <label class="form-label">Tipe Indikator</label>
                    <div class="form-control-plaintext fw-bold text-success"><i class="ti ti-lock me-1"></i>Indikator Renop</div>
                @else
                    <x-tabler.form-select
                        id="type"
                        name="type"
                        label="Tipe Indikator"
                        :options="['standar' => 'Indikator Standar', 'renop' => 'Indikator Renop']"
                        :selected="$isEdit ? $indikator->type : 'standar'"
                        required="true"
                        data-dropdown-parent="#modalAction"
                    />
                @endif
            </div>
            @if($isEdit)
            <div class="col-md-6">
                <x-tabler.form-input type="number" id="urutan" name="urutan" label="Urutan" :value="$indikator->urutan" />
            </div>
            @endif
        </div>


    @endif
</x-tabler.form-modal>

@if($type === 'dokumen' && !$isEdit)
<script>
    $('#jenis').on('change', function() {
        const val = $(this).val();
        const text = $(this).find('option:selected').text().trim();
        const autoTypes = ['visi', 'misi', 'rjp', 'renstra', 'renop'];

        if (autoTypes.includes(val)) {
            let cleanTitle = text;
            if(val === 'rjp') cleanTitle = 'Rencana Pembangunan Jangka Panjang (RPJP)';
            if(val === 'renstra') cleanTitle = 'Rencana Strategis (Renstra)';
            if(val === 'renop') cleanTitle = 'Rencana Operasional (Renop)';
            if(val === 'visi') cleanTitle = 'Visi';
            if(val === 'misi') cleanTitle = 'Misi';

            $('#judul').val(cleanTitle);
        }
    });
</script>
@endif

<script>
    setTimeout(function() {
        if (window.loadHugeRTE) {
            window.loadHugeRTE('#isi', { 
                height: 400,
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save();
                    });
                }
            });
        }
    }, 300); // Wait for Bootstrap modal transition to finish
</script>
