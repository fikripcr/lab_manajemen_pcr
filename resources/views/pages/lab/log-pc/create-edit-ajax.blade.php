<x-tabler.form-modal
    id_form="{{ $log->exists ? 'editLogPcForm' : 'createLogPcForm' }}"
    title="{{ $log->exists ? 'Update Log Penggunaan PC' : 'Isi Log Penggunaan PC' }}"
    route="{{ $log->exists ? route('lab.log-pc.update', $log->encrypted_log_penggunaan_pc_id) : route('lab.log-pc.store') }}"
    method="{{ $log->exists ? 'PUT' : 'POST' }}"
>
    @if(!$activeJadwal && !$log->exists)
        <div class="alert alert-warning" role="alert">
            <div class="d-flex">
                <div>
                    <i class="bx bx-error me-2 h2"></i>
                </div>
                <div>
                    <h4 class="alert-title">Tidak ada jadwal aktif!</h4>
                    <div class="text-secondary">
                        Saat ini tidak ada jadwal perkuliahan yang aktif untuk Anda isi log-nya. 
                    </div>
                </div>
            </div>
        </div>
    @else
        @if(!$log->exists)
            <div class="datagrid mb-3">
                <div class="datagrid-item">
                    <div class="datagrid-title">Mata Kuliah</div>
                    <div class="datagrid-content">{{ $activeJadwal->mataKuliah->nama_mk }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Lab</div>
                    <div class="datagrid-content">{{ $activeJadwal->lab->name }}</div>
                </div>
                <div class="datagrid-item">
                    <div class="datagrid-title">Assignment PC</div>
                    <div class="datagrid-content">
                        @if($assignment)
                            <span class="badge bg-blue text-white">PC {{ $assignment->nomor_pc }}</span>
                        @else
                            <span class="badge bg-warning text-white">Belum ada assignment</span>
                        @endif
                    </div>
                </div>
            </div>

            @if(!$assignment)
                <div class="alert alert-danger py-2">
                    Anda belum ditugaskan ke PC manapun di jadwal ini.
                </div>
            @else
                <input type="hidden" name="jadwal_id" value="{{ encryptId($activeJadwal->jadwal_kuliah_id) }}">
                <input type="hidden" name="lab_id" value="{{ encryptId($activeJadwal->lab_id) }}">
                
                <div class="mb-3">
                    <label class="form-label required">Kondisi PC Saat Ini</label>
                    <div class="form-selectgroup">
                        <label class="form-selectgroup-item">
                            <input type="radio" name="status_pc" value="Baik" class="form-selectgroup-input" checked>
                            <span class="form-selectgroup-label">
                                <i class="ti ti-check me-1 text-success"></i> Baik
                            </span>
                        </label>
                        <label class="form-selectgroup-item">
                            <input type="radio" name="status_pc" value="Rusak" class="form-selectgroup-input">
                            <span class="form-selectgroup-label">
                                <i class="ti ti-x me-1 text-danger"></i> Rusak / Bermasalah
                            </span>
                        </label>
                    </div>
                </div>

                <x-tabler.form-textarea 
                    name="catatan_umum" 
                    label="Catatan (Opsional)" 
                    rows="3" 
                    placeholder="Contoh: Mouse agak macet, Keyboard tombol A keras..." 
                />
            @endif
        @else
            {{-- Update logic for admin maybe? --}}
            <div class="mb-3">
                <x-tabler.form-select 
                    name="status_pc" 
                    label="Status PC" 
                    :options="['Baik' => 'Baik', 'Rusak' => 'Rusak']" 
                    selected="{{ $log->status_pc }}"
                    required
                />
            </div>
            <x-tabler.form-textarea 
                name="catatan_umum" 
                label="Catatan Umum" 
                rows="3" 
            >{{ old('catatan_umum', $log->catatan_umum) }}</x-tabler.form-textarea>
        @endif
    @endif
</x-tabler.form-modal>
