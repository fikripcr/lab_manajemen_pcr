@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Tim Mutu" pretitle="Penjaminan Mutu"/>
@endsection

@section('content')
<div class="row row-cards mb-4">
    <div class="col-md-6">
        <x-tabler.card>
            <x-tabler.card-header title="Pilih Periode SPMI" />
            <x-tabler.card-body>
                <x-tabler.form-select name="periode_id" label="Periode" id="periode-select">
                    <option value="">Pilih Periode...</option>
                    @foreach($periodes as $p)
                        <option value="{{ $p->encrypted_periodespmi_id }}" {{ $activePeriode && $activePeriode->periodespmi_id == $p->periodespmi_id ? 'selected' : '' }}>
                            {{ $p->periode }} — {{ ucfirst($p->jenis_periode) }}
                        </option>
                    @endforeach
                </x-tabler.form-select>
                <x-tabler.button type="button" class="btn-primary w-100 mt-3" id="btn-manage" icon="ti ti-arrow-right" text="Kelola Tim Mutu" />
            </x-tabler.card-body>
        </x-tabler.card>
    </div>

    @if($summary)
    <div class="col-md-6">
        <x-tabler.card>
            <x-tabler.card-header title="Ringkasan — {{ $activePeriode->periode }} {{ ucfirst($activePeriode->jenis_periode) }}" />
            <x-tabler.card-body>
                <div class="row text-center">
                    <div class="col-3">
                        <div class="h1 mb-0 text-primary">{{ $summary['total_units'] }}</div>
                        <div class="text-muted small">Unit Kerja</div>
                    </div>
                    <div class="col-3">
                        <div class="h1 mb-0 text-success">{{ $summary['total_auditee'] }}</div>
                        <div class="text-muted small">Auditee</div>
                    </div>
                    <div class="col-3">
                        <div class="h1 mb-0 text-info">{{ $summary['total_anggota'] }}</div>
                        <div class="text-muted small">Anggota</div>
                    </div>
                    <div class="col-3">
                        <div class="h1 mb-0 text-purple">{{ $summary['total_pegawai'] }}</div>
                        <div class="text-muted small">Pegawai</div>
                    </div>
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('btn-manage').addEventListener('click', function() {
        var periodeId = document.getElementById('periode-select').value;
        if (!periodeId) {
            alert('Pilih periode terlebih dahulu.');
            return;
        }
        window.location.href = '{{ url("pemutu/tim-mutu") }}/' + periodeId + '/manage';
    });
</script>
@endpush
