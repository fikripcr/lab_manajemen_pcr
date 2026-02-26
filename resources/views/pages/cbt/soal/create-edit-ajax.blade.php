@php
    $isEdit = $soal->exists;
    $title = ($isEdit ? 'Edit' : 'Tambah') . ' Soal ' . str_replace('_', ' ', $soal->tipe_soal);
    $route = $isEdit ? route('cbt.soal.update', $soal->encrypted_soal_id) : route('cbt.soal.store', $soal->mataUji->encrypted_mata_uji_id);
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    size="modal-lg"
>
    <input type="hidden" name="mata_uji_id" value="{{ $soal->mataUji->encrypted_mata_uji_id }}">
    <input type="hidden" name="tipe_soal" value="{{ $soal->tipe_soal }}">

    <div class="row">
        <div class="col-md-12 mb-2">
            <label class="form-label required mb-1">Konten Pertanyaan</label>
            <x-tabler.form-textarea name="konten_pertanyaan" id="konten_pertanyaan" :value="$soal->konten_pertanyaan" required="true" />
        </div>
    </div>

    @if($soal->tipe_soal === 'Pilihan_Ganda')
        <div class="mt-2">
            <label class="form-label required">Opsi Jawaban & Kunci</label>
            <div class="table-responsive">
                <table class="table table-vcenter table-sm card-table">
                    <thead>
                        <tr>
                            <th width="50">Kunci</th>
                            <th width="50">Label</th>
                            <th>Teks Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(['A', 'B', 'C', 'D', 'E'] as $label)
                        @php
                            $opsi = $isEdit ? $soal->opsiJawaban->where('label', $label)->first() : null;
                        @endphp
                        <tr>
                            <td class="text-center">
                                <x-tabler.form-radio name="kunci_jawaban" :value="$label" :checked="($opsi && $opsi->is_kunci_jawaban) || (!$isEdit && $label === 'A')" />
                            </td>
                            <td class="fw-bold">{{ $label }}</td>
                            <td>
                                <x-tabler.form-input type="text" name="opsi[{{ $label }}]" :value="$opsi ? $opsi->teks_jawaban : ''" placeholder="Teks jawaban {{ $label }}" required />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($soal->tipe_soal === 'Benar_Salah')
        <div class="row">
            <div class="col-md-12">
                <x-tabler.form-select name="kunci_jawaban" label="Kunci Jawaban" required="true">
                    <option value="Benar" {{ ($isEdit && $soal->kunci_jawaban === 'Benar') ? 'selected' : '' }}>Benar</option>
                    <option value="Salah" {{ ($isEdit && $soal->kunci_jawaban === 'Salah') ? 'selected' : '' }}>Salah</option>
                </x-tabler.form-select>
            </div>
        </div>
    @elseif($soal->tipe_soal === 'Esai')
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-2"></i> Soal tipe Esai tidak memerlukan kunci jawaban pilihan. Jawaban akan dinilai manual oleh pemeriksa.
                </div>
            </div>
        </div>
    @endif

    <div class="row mt-3">
        <div class="col-md-12">
            <label class="form-label required">Tingkat Kesulitan</label>
            <div class="form-selectgroup">
                @foreach(['Mudah', 'Sedang', 'Sulit'] as $lvl)
                <label class="form-selectgroup-item">
                    <input type="radio" name="tingkat_kesulitan" value="{{ $lvl }}" class="form-selectgroup-input" {{ ($isEdit && $soal->tingkat_kesulitan === $lvl) || (!$isEdit && $lvl === 'Sedang') ? 'checked' : '' }}>
                    <span class="form-selectgroup-label">{{ $lvl }}</span>
                </label>
                @endforeach
            </div>
        </div>
    </div>
</x-tabler.form-modal>

@push('js')
<script>
    if (window.loadHugeRTE) {
        window.loadHugeRTE('#konten_pertanyaan', { 
            height: 250,
            toolbar: 'bold italic table forecolor | alignleft aligncenter alignright alignjustify | bullist numlist | fullscreen'
        });
    }
</script>
@endpush
