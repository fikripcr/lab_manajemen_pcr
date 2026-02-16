@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Edit Soal</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="{{ route('cbt.soal.update', $soal->encrypted_id) }}" method="POST" class="ajax-form" data-redirect="{{ route('cbt.soal.index') }}">
            @csrf
            @method('PUT')
            <div class="row row-cards">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <x-tabler.form-textarea name="konten_pertanyaan" label="Konten Pertanyaan" type="editor" value="{!! $soal->konten_pertanyaan !!}" required="true" />
                            
                            <div class="mt-4" id="section-pilihan-ganda" style="{{ $soal->tipe_soal == 'Pilihan_Ganda' ? '' : 'display:none;' }}">
                                <h3>Opsi Jawaban (Pilihan Ganda)</h3>
                                <div class="table-responsive">
                                    <table class="table table-vcenter">
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
                                                $opsi = $soal->opsiJawaban->where('label', $label)->first();
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="radio" name="kunci_jawaban" value="{{ $label }}" class="form-check-input" {{ ($opsi && $opsi->is_kunci_jawaban) ? 'checked' : '' }}>
                                                </td>
                                                <td><strong>{{ $label }}</strong></td>
                                                <td>
                                                    <input type="text" name="opsi[{{ $label }}]" class="form-control" value="{{ $opsi ? $opsi->teks_jawaban : '' }}" placeholder="Teks jawaban untuk {{ $label }}">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Mata Uji</label>
                                <select name="mata_uji_id" class="form-select" required>
                                    @foreach($mataUji as $mu)
                                        <option value="{{ $mu->encrypted_id }}" {{ $mu->id == $soal->mata_uji_id ? 'selected' : '' }}>{{ $mu->nama_mata_uji }} ({{ $mu->tipe }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Tipe Soal</label>
                                <select name="tipe_soal" id="tipe_soal" class="form-select" required>
                                    <option value="Pilihan_Ganda" {{ $soal->tipe_soal == 'Pilihan_Ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                                    <option value="Esai" {{ $soal->tipe_soal == 'Esai' ? 'selected' : '' }}>Esai</option>
                                    <option value="Benar_Salah" {{ $soal->tipe_soal == 'Benar_Salah' ? 'selected' : '' }}>Benar / Salah</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Tingkat Kesulitan</label>
                                <select name="tingkat_kesulitan" class="form-select" required>
                                    <option value="Mudah" {{ $soal->tingkat_kesulitan == 'Mudah' ? 'selected' : '' }}>Mudah</option>
                                    <option value="Sedang" {{ $soal->tingkat_kesulitan == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                    <option value="Sulit" {{ $soal->tingkat_kesulitan == 'Sulit' ? 'selected' : '' }}>Sulit</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-device-floppy"></i> Update Soal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#tipe_soal').on('change', function() {
            if ($(this).val() == 'Pilihan_Ganda') {
                $('#section-pilihan-ganda').show();
            } else {
                $('#section-pilihan-ganda').hide();
            }
        });
    });
</script>
@endpush
