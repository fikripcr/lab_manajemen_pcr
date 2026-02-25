@php
    $isEdit = false;
    $title = '';
    $route = '';
    $method = 'POST';

    // TYPE: 'dokumen', 'poin', 'indikator'
    if (!isset($type)) {
        $type = 'dokumen';
    }

    if ($type === 'dokumen') {
        $isEdit = isset($dokumen) && $dokumen->exists;
        $title = $isEdit ? "Ubah Dokumen" : "Tambah Dokumen";
        $route = $isEdit ? route('pemutu.dokumen-spmi.update', ['type' => 'dokumen', 'id' => $dokumen->encrypted_dok_id]) : route('pemutu.dokumen-spmi.store', ['type' => 'dokumen']);
        $method = $isEdit ? 'PUT' : 'POST';
    } elseif ($type === 'poin') {
        $isEdit = isset($dokSub) && $dokSub->exists;
        $title = $isEdit ? "Ubah Poin" : "Tambah Poin / Kegiatan";
        $route = $isEdit ? route('pemutu.dokumen-spmi.update', ['type' => 'poin', 'id' => $dokSub->encrypted_doksub_id]) : route('pemutu.dokumen-spmi.store', ['type' => 'poin']);
        $method = $isEdit ? 'PUT' : 'POST';
    } elseif ($type === 'indikator') {
        $isEdit = isset($indikator) && $indikator->exists;
        $title = $isEdit ? "<br>Ubah Indikator" : "Tambah Indikator";
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
    size="{{ $type === 'indikator' ? 'lg' : 'md' }}"
>
    {{-- A. DOKUMEN FORM --}}
    @if($type === 'dokumen')
        @if(!$isEdit)
            <input type="hidden" name="parent_id" value="{{ isset($parent) ? $parent->encrypted_dok_id : '' }}">
            @if(isset($parentDokSub))
                <input type="hidden" name="parent_doksub_id" value="{{ $parentDokSub->encrypted_doksub_id }}">
            @endif
            @if(isset($fixedJenis))
                <input type="hidden" name="jenis" value="{{ $fixedJenis }}">
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
                        :options="$allowedTypes"
                        :selected="null"
                        placeholder="Pilih Jenis..."
                    />
                </div>
            @endif
            <input type="hidden" name="periode" value="{{ date('Y') }}">
        @else
            <input type="hidden" name="jenis" value="{{ $dokumen->jenis }}">
            <input type="hidden" name="periode" value="{{ $dokumen->periode }}">
            <div class="mb-3">
                <label class="form-label">Jenis Dokumen</label>
                <div class="form-control-plaintext fw-bold">{{ ucfirst($dokumen->jenis) }}</div>
            </div>
            <div class="mb-3">
                <x-tabler.form-select id="parent_id" name="parent_id" label="Induk Dokumen (Parent)" type="select2" data-dropdown-parent="#modalAction">
                    <option value="">Tanpa Induk (Root)</option>
                    @foreach($dokumens as $d)
                        <option value="{{ $d->encrypted_dok_id }}" {{ $dokumen->parent_id == $d->dok_id ? 'selected' : '' }}>
                            {{ $d->judul }}
                        </option>
                    @endforeach
                </x-tabler.form-select>
            </div>
        @endif

        <div class="mb-3">
            <x-tabler.form-input name="judul" id="judul" label="Judul Dokumen" :value="$isEdit ? $dokumen->judul : ''" placeholder="Contoh: Manual Mutu" required="true" />
        </div>

        <div class="mb-3">
            <x-tabler.form-input name="kode" id="kode" label="Kode Dokumen" :value="$isEdit ? $dokumen->kode : ''" placeholder="Contoh: MM-01" />
        </div>

        @if($isEdit)
            <div class="mb-3">
                <x-tabler.form-textarea name="isi" id="isi" type="editor" height="400" rows="10" :value="$dokumen->isi" />
            </div>
        @endif

    {{-- B. POIN (DOKSUB) FORM --}}
    @elseif($type === 'poin')
        @php
            $jenisPoin = strtolower(trim($isEdit ? $dokSub->dokumen->jenis : $dokumen->jenis));
            $canProduceIndikator = in_array($jenisPoin, ['standar', 'formulir', 'manual_prosedur', 'renop']);
        @endphp

        @if(!$isEdit)
            <input type="hidden" name="dok_id" value="{{ $dokumen->encrypted_dok_id }}">
        @endif

        <div class="row g-2">
            <div class="col-md-12">
                <x-tabler.form-textarea
                    name="judul"
                    label="Judul"
                    id="judul"
                    :value="$isEdit ? $dokSub->judul : ''"
                    required="true"
                    placeholder="{{ $canProduceIndikator ? 'Contoh: Standar Kompetensi Lulusan' : 'Contoh: Misi 1' }}"
                />
            </div>
            <div class="col-md-4">
                <x-tabler.form-input name="kode" id="kode" label="Kode" :value="$isEdit ? $dokSub->kode : ''" placeholder="Contoh: S.01" />
            </div>
        </div>

        @if($canProduceIndikator)
        <div class="mb-3 mt-3">
            <x-tabler.form-checkbox
                name="is_hasilkan_indikator"
                label="Hasilkan Indikator?"
                value="1"
                :checked="$isEdit ? $dokSub->is_hasilkan_indikator : ($jenisPoin === 'renop' || $jenisPoin === 'standar')"
                switch
            />
            <div class="text-muted small">Jika dicentang, poin ini bisa ditambahkan Indikator.</div>
        </div>
        @endif

        @if(!$canProduceIndikator || $jenisPoin === 'renop' || $isEdit)
        <div class="mt-3">
            <x-tabler.form-textarea
                :type="$isEdit ? 'editor' : 'textarea'"
                name="isi"
                id="isi"
                label="{{ $isEdit ? $dokSub->isi : '' }}"
                :value="$isEdit ? $dokSub->isi : ''"
                rows="4"
                placeholder="Isi poin..."
                :height="$isEdit ? 300 : null"
            />
        </div>
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
