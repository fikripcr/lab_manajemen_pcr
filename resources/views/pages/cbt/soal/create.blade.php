@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Tambah Soal Baru" pretitle="CBT">
    <x-slot:actions>
        @php
            $backRoute = $selectedMataUji ? route('cbt.mata-uji.show', $selectedMataUji) : route('cbt.mata-uji.index');
        @endphp
        <x-tabler.button href="{{ $backRoute }}" class="btn-outline-secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

<div class="page-body">
    <div class="container-xl">
        <form action="{{ route('cbt.soal.store') }}" method="POST" class="ajax-form" data-redirect="{{ $backRoute }}">
            @csrf
            <div class="row row-cards">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <x-tabler.form-textarea name="konten_pertanyaan" label="Konten Pertanyaan" type="editor" required="true" />
                            
                            <div class="mt-4" id="section-pilihan-ganda">
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
                                            <tr>
                                                <td>
                                                    <input type="radio" name="kunci_jawaban" value="{{ $label }}" class="form-check-input" {{ $label == 'A' ? 'checked' : '' }}>
                                                </td>
                                                <td><strong>{{ $label }}</strong></td>
                                                <td>
                                                    <x-tabler.form-input name="opsi[{{ $label }}]" placeholder="Teks jawaban untuk {{ $label }}" />
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
                            <x-tabler.form-select name="mata_uji_id" label="Mata Uji" required="true">
                                @foreach($mataUji as $mu)
                                    <option value="{{ $mu->encrypted_mata_uji_id }}" {{ $selectedMataUji == $mu->encrypted_mata_uji_id ? 'selected' : '' }}>
                                        {{ $mu->nama_mata_uji }} ({{ $mu->tipe }})
                                    </option>
                                @endforeach
                            </x-tabler.form-select>

                            <x-tabler.form-select name="tipe_soal" id="tipe_soal" label="Tipe Soal" required="true">
                                <option value="Pilihan_Ganda">Pilihan Ganda</option>
                                <option value="Esai">Esai</option>
                                <option value="Benar_Salah">Benar / Salah</option>
                            </x-tabler.form-select>

                            <x-tabler.form-select name="tingkat_kesulitan" label="Tingkat Kesulitan" required="true">
                                <option value="Mudah">Mudah</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Sulit">Sulit</option>
                            </x-tabler.form-select>

                            <x-tabler.button type="submit" class="btn-primary w-100 mb-2" icon="ti ti-device-floppy" text="Simpan Soal" />
                            <x-tabler.button type="button" id="btn-save-another" class="btn-outline-primary w-100" icon="ti ti-plus" text="Simpan & Tambah Lagi" />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tipe_soal').on('change', function() {
            if ($(this).val() == 'Pilihan_Ganda') {
                $('#section-pilihan-ganda').show();
            } else {
                $('#section-pilihan-ganda').hide();
            }
        $('#btn-save-another').on('click', function() {
            var form = $(this).closest('form');
            var oldRedirect = form.data('redirect');
            form.attr('data-redirect', window.location.href);
            form.submit();
            // Restore for future submits if any (though page will reload)
            form.attr('data-redirect', oldRedirect);
        });
    });
</script>
@endpush
