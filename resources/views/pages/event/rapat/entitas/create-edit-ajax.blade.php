@php
    $selectedEntityText = $selectedEntityText ?? '';
    $selectedEntityId   = $selectedEntityId ?? '';
@endphp

<x-tabler.form-modal 
    :title="$entitas->exists ? 'Edit Entitas Terkait' : 'Tambah Entitas Terkait'" 
    :route="$entitas->exists ? route('Kegiatan.rapat.entitas.update', [$rapat->encrypted_rapat_id, $entitas->encrypted_rapatentitas_id]) : route('Kegiatan.rapat.entitas.store', $rapat->encrypted_rapat_id)" 
    :method="$entitas->exists ? 'PUT' : 'POST'"
>
    <input type="hidden" name="rapat_id" value="{{ $rapat->encrypted_rapat_id }}">
    
    <div class="row">
        <div class="col-md-12 mb-3">
            <x-tabler.form-select 
                name="entity" 
                id="entity_search" 
                label="Cari Entitas (Indikator)" 
                required="true"
            >
                @if($selectedEntityId)
                    <option value="{{ $selectedEntityId }}" selected>{{ $selectedEntityText }}</option>
                @endif
            </x-tabler.form-select>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <x-tabler.form-textarea 
                name="keterangan" 
                label="Keterangan" 
                :value="old('keterangan', $entitas->keterangan)"
                placeholder="Masukkan keterangan (opsional)" 
                rows="3" 
            />
        </div>
    </div>

    <script>
        if (typeof window.loadSelect2 === 'function') {
            window.loadSelect2().then(function() {
                $('#entity_search').select2({
                    dropdownParent: $('#modalContent'),
                    placeholder: 'Cari Nomor atau Nama Indikator...',
                    allowClear: true,
                    width: '100%',
                    ajax: {
                        url: '{{ route('Kegiatan.rapat.entitas.search', $rapat->encrypted_rapat_id) }}',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return { q: params.term };
                        },
                        processResults: function (data) {
                            return { results: data.results };
                        },
                        cache: true
                    }
                });
            });
        }
    </script>
</x-tabler.form-modal>
