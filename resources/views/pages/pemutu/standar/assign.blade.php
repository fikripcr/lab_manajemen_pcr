@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Penugasan Indikator Standar" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.standar.index') }}" style="secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="mb-3">
    <div class="card card-stacked">
        <div class="card-body">
            <div class="fw-bold">Indikator:</div>
            <div class="h3">{{ $indikator->indikator }}</div>
            <div class="text-muted small">Tipe: {{ ucfirst($indikator->type) }}</div>
        </div>
    </div>
</div>

<div class="row row-cards">
    <div class="col-md-5">
        <form method="POST" action="{{ route('pemutu.standar.assign.store', $indikator->encrypted_indikator_id) }}" class="card ajax-form">
            @csrf
            <div class="card-header">
                <h3 class="card-title">Penugasan Unit Kerja & Target</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive border-bottom" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-vcenter table-striped">
                        <thead>
                            <tr>
                                <th width="60%" class="ps-3">Unit Kerja</th>
                                <th width="40%">Target</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                if(!function_exists('renderUnitRowAssign')){
                                    function renderUnitRowAssign($unit, $level = 0, $assignments = []) {
                                        $padding = $level * 20;
                                        $isBold = $level < 2 ? 'fw-bold' : '';
                                        
                                        $isChecked = isset($assignments[$unit->orgunit_id]);
                                        $targetVal = $isChecked ? $assignments[$unit->orgunit_id]->pivot->target : '';
                                        $disabled = !$isChecked ? 'disabled' : '';
 
                                        echo '<tr>';
                                        echo '<td>';
                                        echo '<div style="padding-left: '.$padding.'px" class="ps-2">';
                                        echo '<label class="form-check form-check-inline mb-0">';
                                        echo '<input class="form-check-input unit-checkbox" type="checkbox" name="assignments['.$unit->encrypted_org_unit_id.'][selected]" value="1" data-id="'.$unit->encrypted_org_unit_id.'" '.($isChecked ? 'checked' : '').'>';
                                        echo '<span class="form-check-label '.$isBold.'">'.$unit->name.'</span>';
                                        echo '</label>';
                                        echo '</div>';
                                        echo '</td>';
                                        echo '<td>';
                                        echo '<input type="text" class="form-control form-control-sm" name="assignments['.$unit->encrypted_org_unit_id.'][target]" id="target-'.$unit->encrypted_org_unit_id.'" value="'.$targetVal.'" placeholder="Target..." '.$disabled.'>';
                                        echo '</td>';
                                        echo '</tr>';

                                        if ($unit->children && $unit->children->count()) {
                                            foreach($unit->children as $child) {
                                                renderUnitRowAssign($child, $level + 1, $assignments);
                                            }
                                        }
                                    }
                                }
                            @endphp

                            @foreach($orgUnits as $rootUnit)
                                {{ renderUnitRowAssign($rootUnit, 0, $assignments) }}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <x-tabler.button type="submit" class="btn-primary" icon="ti ti-device-floppy" text="Simpan Penugasan" />
            </div>
        </form>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Unit yang Ditugaskan</h3>
            </div>
             <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Unit Kerja</th>
                            <th>Target Spesifik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($indikator->orgUnits as $unit)
                        <tr>
                            <td>
                                <div class="font-weight-medium">{{ $unit->name }}</div>
                                <div class="text-muted small">{{ $unit->parent ? 'Sub dari: ' . $unit->parent->name : 'Unit Utama' }}</div>
                            </td>
                            <td>
                                @if($unit->pivot->target)
                                    <span class="badge bg-green-lt">{{ $unit->pivot->target }}</span>
                                @else
                                    <span class="text-muted fst-italic">Mengikuti Indikator</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted p-4">Belum ada unit yang ditugaskan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.unit-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const unitId = this.dataset.id;
                const targetInput = document.getElementById('target-' + unitId);
                if (this.checked) {
                    targetInput.removeAttribute('disabled');
                    if(!targetInput.value) targetInput.focus();
                } else {
                    targetInput.setAttribute('disabled', 'disabled');
                }
            });
        });
    });
</script>
@endpush
