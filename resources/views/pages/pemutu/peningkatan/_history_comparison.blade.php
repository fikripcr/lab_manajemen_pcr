@php
    $curr = $comparison['current'];
    $prev = $comparison['prev'];
    $isIndikatorChanged = $prev && $curr['text'] !== $prev['text'];
    $isTargetChanged = $prev && $curr['target'] !== $prev['target'];
@endphp

<div class="modal-body p-0">
    <!-- Context Header -->
    <div class="bg-primary-lt p-4 border-bottom">
        <h3 class="mb-1 fw-bold text-primary">{{ $curr['unit_name'] }}</h3>
        <div class="text-muted small d-flex align-items-center gap-2">
            <span>{{ $curr['unit_code'] }}</span>
        </div>
    </div>

    @if($prev && $prev['analisis_atsn'])
    <div class="bg-info-lt p-3 px-4 border-bottom shadow-inner text-info">
        <div class="d-flex gap-2">
            <i class="ti ti-message-dots fs-3 mt-1"></i>
            <div>
                <strong class="d-block mb-1">Catatan Evaluasi AMI Lalu:</strong>
                <span class="fst-italic">{{ $prev['analisis_atsn'] }}</span>
            </div>
        </div>
    </div>
    @endif

    <div class="p-4 table-responsive">
        <table class="table table-vcenter table-bordered card-table table-striped mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="w-25 text-muted">Komponen</th>
                    <th class="w-25 text-center text-muted">Sebelum Duplikat <br><small class="fw-normal">(Status AMI: <strong>{{ ucwords($prev['status'] ?? '-') }}</strong>)</small></th>
                    <th class="w-1 text-center border-0 px-0"></th>
                    <th class="w-25 text-center text-primary">Sesudah Duplikat <br><small class="fw-normal">(Draft Terbaru)</small></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fw-bold">Pernyataan Indikator</td>
                    @if($isIndikatorChanged)
                        <td class="text-muted fst-italic">{{ $prev['text'] }}</td>
                        <td class="text-center px-1"><i class="ti ti-arrow-right text-muted"></i></td>
                        <td class="fw-bold text-primary">{{ $curr['text'] }}</td>
                    @else
                        <td colspan="3" class="text-center text-muted fst-italic py-4">
                            {{ $curr['text'] }} 
                            <div class="badge bg-green-lt mt-2"><i class="ti ti-check me-1"></i> Tidak ada perubahan</div>
                        </td>
                    @endif
                </tr>
                <tr>
                    <td class="fw-bold">Target</td>
                    @if($isTargetChanged)
                        <td class="text-center h3 mb-0 text-muted">{{ $prev['target'] ?? '-' }}</td>
                        <td class="text-center px-1"><i class="ti ti-arrow-right text-muted"></i></td>
                        <td class="text-center h3 mb-0 text-primary">{{ $curr['target'] ?? '-' }}</td>
                    @else
                        <td colspan="3" class="text-center text-muted fst-italic py-4">
                            <span class="h3">{{ $curr['target'] ?? '-' }}</span>
                            <div class="badge bg-green-lt mt-2"><i class="ti ti-check me-1"></i> Tidak ada perubahan</div>
                        </td>
                    @endif
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal-footer bg-light border-top d-flex justify-content-between p-3 mt-0">
    <div class="text-muted small">
        <i class="ti ti-info-circle me-1"></i>
        Perbandingan indikator ini ditampilkan untuk unit terkait dari siklus sebelumnya.
    </div>
    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Tutup</button>
</div>
