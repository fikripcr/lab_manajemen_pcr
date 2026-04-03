@php
    $item = $indikOrgUnit;
    $indikator = $item->indikator;
    $route = route('pemutu.peningkatan.review-update', encryptId($item->indikorgunit_id));
@endphp

<x-tabler.form-modal
    title="Edit Indikator Peningkatan"
    :route="$route"
    method="PUT"
    submitText="Simpan Perubahan"
>
    <div class="mb-4">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-primary-lt">{{ $indikator->no_indikator }}</span>
            <span class="badge bg-secondary-lt">{{ $item->orgUnit->name ?? '-' }}</span>
        </div>
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            name="indikator" 
            label="Deskripsi Indikator" 
            type="textarea" 
            value="{{ old('indikator', $indikator->indikator ?? '') }}"
            placeholder="Deskripsi indikator" 
        />
        <small class="text-muted">Ubah jika diperlukan penyesuaian di periode baru.</small>
    </div>

    <div class="mb-3">
        <x-tabler.form-input 
            name="target" 
            label="Target" 
            type="text" 
            value="{{ old('target', $item->target ?? '') }}"
            placeholder="Target indikator" 
        />
    </div>

    @if($item->prev_indikorgunit_id)
        @php
            $prevOu = \App\Models\Pemutu\IndikatorOrgUnit::find($item->prev_indikorgunit_id);
        @endphp
        @if($prevOu)
            <div class="alert alert-info">
                <div class="d-flex align-items-center mb-1">
                    <i class="ti ti-history me-2"></i>
                    <strong>Referensi Periode Sebelumnya</strong>
                </div>
                <div class="small">
                    <div><strong>Target Lama:</strong> {{ $prevOu->target ?? '-' }}</div>
                    @if($prevOu->pengend_status_atsn)
                        <div><strong>Status Pengendalian:</strong>
                            <span class="badge bg-{{ $prevOu->pengend_status_atsn === 'Nonaktif' ? 'red' : ($prevOu->pengend_status_atsn === 'Penyesuaian' ? 'yellow' : 'green') }}-lt">
                                {{ $prevOu->pengend_status_atsn }}
                            </span>
                        </div>
                    @endif
                    @if($prevOu->pengend_analisis_atsn)
                        <div class="mt-1"><strong>Analisis Atasan:</strong> {{ $prevOu->pengend_analisis_atsn }}</div>
                    @endif
                </div>
            </div>
        @endif
    @endif
</x-tabler.form-modal>
