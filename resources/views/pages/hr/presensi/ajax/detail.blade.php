<div class="row">
    <div class="col-md-6">
        <h6 class="mb-3">Informasi Presensi</h6>
        <table class="table table-sm table-borderless">
            <tr>
                <td class="text-muted" width="40%">Tanggal</td>
                <td class="fw-bold">: {{ $data['date'] }}</td>
            </tr>
            <tr>
                <td class="text-muted">Shift</td>
                <td>: {{ $data['shift'] }}</td>
            </tr>
            <tr>
                <td class="text-muted">Status</td>
                <td>: 
                    @php
                        $statusMap = [
                            'on_time' => ['label' => 'Tepat Waktu', 'class' => 'bg-success'],
                            'late' => ['label' => 'Terlambat', 'class' => 'bg-warning'],
                            'absent' => ['label' => 'Tidak Hadir', 'class' => 'bg-danger'],
                        ];
                        $status = $statusMap[$data['status']] ?? ['label' => '-', 'class' => 'bg-secondary'];
                    @endphp
                    <span class="badge {{ $status['class'] }} text-white">{{ $status['label'] }}</span>
                </td>
            </tr>
            <tr>
                <td class="text-muted">Durasi</td>
                <td>: {{ $data['duration'] }}</td>
            </tr>
            <tr>
                <td class="text-muted">Catatan</td>
                <td>: {{ $data['notes'] }}</td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="mb-2">Lokasi Check In</h6>
        <div class="p-2 border rounded mb-3">
            <div class="small mb-1">
                <i class="ti ti-clock me-1 text-success"></i><strong>Waktu:</strong> {{ $data['check_in'] }}
            </div>
            <div class="small mb-1">
                <i class="ti ti-map-pin me-1 text-muted"></i><strong>Alamat:</strong>
            </div>
            <div class="small text-muted ps-3">
                {{ $data['check_in_location'] }}
            </div>
        </div>

        <h6 class="mb-2">Lokasi Check Out</h6>
        <div class="p-2 border rounded">
            <div class="small mb-1">
                <i class="ti ti-clock me-1 text-danger"></i><strong>Waktu:</strong> {{ $data['check_out'] }}
            </div>
            <div class="small mb-1">
                <i class="ti ti-map-pin me-1 text-muted"></i><strong>Alamat:</strong>
            </div>
            <div class="small text-muted ps-3">
                {{ $data['check_out_location'] }}
            </div>
        </div>
    </div>
</div>
