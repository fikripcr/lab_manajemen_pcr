{{-- Partial for Duplikasi Standar Content --}}
<div class="alert alert-info mb-3">
    <ul class="mb-0">
        <li>Standar yang muncul sesuai dengan kelompok periode ini: <strong>{{ $periode->jenis_periode }}</strong></li>
        <li>Silahkan checklist standar pada bagian <strong>"STANDAR SEBELUMNYA"</strong> lalu klik <strong>"DUPLIKASI STANDAR"</strong> untuk duplikasi ke periode selanjutnya</li>
    </ul>
</div>

<div class="row mb-3 align-items-center">
    <div class="col-md-6">
        <div class="d-flex align-items-center">
            <span class="badge bg-blue-lt me-2 p-2">
                <i class="ti ti-calendar-share me-1"></i> Target Duplikasi: <strong>{{ $periode->periode + 1 }}</strong>
            </span>
            <input type="hidden" id="input-target-periode-{{ $typeId }}" value="{{ $periode->periode + 1 }}">
            <span id="duplikasi-status-{{ $typeId }}" class="text-muted small ms-2"></span>
        </div>
    </div>
    <div class="col-md-6 text-end">
        <x-tabler.button type="button" class="btn-primary" id="btn-duplikasi-{{ $typeId }}"
            icon="ti ti-copy" text="Duplikasi Terpilih" disabled="true" />
    </div>
</div>

<div class="row panel-standar-container" data-type="{{ $typeId }}" data-periode-id="{{ $periode->encrypted_periodespmi_id }}">
    {{-- Panel Kiri: STANDAR SEBELUMNYA --}}
    <div class="col-md-6">
        <x-tabler.card class="border-2 border-secondary shadow-none">
            <x-tabler.card-header class="bg-secondary-lt">
                <div class="d-flex w-100 align-items-center">
                    <h4 class="card-title mb-0"><i class="ti ti-history me-2"></i>STANDAR SEBELUMNYA</h4>
                    <div class="ms-auto d-flex gap-2">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Aksi</button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0)" class="dropdown-item btn-check-all-lama" data-type="{{ $typeId }}"><i class="ti ti-check me-2"></i>Pilih Semua</a>
                                <a href="javascript:void(0)" class="dropdown-item btn-uncheck-all-lama" data-type="{{ $typeId }}"><i class="ti ti-square me-2"></i>Bersihkan</a>
                            </div>
                        </div>
                        <input type="text" class="form-control form-control-sm search-standar-lama" data-type="{{ $typeId }}"
                            placeholder="Search..." style="width: 120px">
                    </div>
                </div>
            </x-tabler.card-header>
            <x-tabler.card-body class="p-0 list-standar-lama" id="list-standar-lama-{{ $typeId }}" style="min-height: 200px; max-height: 500px; overflow-y: auto;">
                <div class="text-center py-5 text-muted">
                    <i class="ti ti-loader ti-spin fs-2 d-block mb-2"></i> Memuat...
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>

    {{-- Panel Kanan: STANDAR BARU --}}
    <div class="col-md-6">
        <x-tabler.card class="border-2 border-success shadow-none">
            <x-tabler.card-header class="bg-green-lt">
                <div class="d-flex w-100 align-items-center">
                    <h4 class="card-title mb-0"><i class="ti ti-sparkles me-2"></i>STANDAR BARU</h4>
                    <span class="badge bg-green ms-2">{{ $periode->periode + 1 }}</span>
                    <div class="ms-auto d-flex gap-2">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-success dropdown-toggle" data-bs-toggle="dropdown">Aksi</button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0)" class="dropdown-item btn-check-all-baru" data-type="{{ $typeId }}"><i class="ti ti-check me-2"></i>Pilih Semua</a>
                                <a href="javascript:void(0)" class="dropdown-item btn-uncheck-all-baru" data-type="{{ $typeId }}"><i class="ti ti-square me-2"></i>Bersihkan</a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0)" class="dropdown-item text-danger disabled btn-hapus-bulk" data-type="{{ $typeId }}"><i class="ti ti-trash me-2 text-red"></i>Hapus Terpilih</a>
                            </div>
                        </div>
                        <input type="text" class="form-control form-control-sm search-standar-baru" data-type="{{ $typeId }}"
                            placeholder="Search..." style="width: 120px">
                    </div>
                </div>
            </x-tabler.card-header>
            <x-tabler.card-body class="p-0 list-standar-baru" id="list-standar-baru-{{ $typeId }}" style="min-height: 200px; max-height: 500px; overflow-y: auto;">
                <div class="text-center py-5 text-muted">
                    <i class="ti ti-loader ti-spin fs-2 d-block mb-2"></i> Memuat...
                </div>
            </x-tabler.card-body>
        </x-tabler.card>
    </div>
</div>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const managers = {};

    class DuplikasiManager {
        constructor(typeId, periodeEncId) {
            this.typeId = typeId;
            this.periodeEncId = periodeEncId;
            this.targetPeriode = document.getElementById(`input-target-periode-${typeId}`).value;
            this.selectedLama = new Set();
            this.selectedBaru = new Set();
            this.allLama = [];
            this.allBaru = [];
            
            this.bindEvents();
            this.loadData();
        }

        bindEvents() {
            const container = document.querySelector(`.panel-standar-container[data-type="${this.typeId}"]`);
            
            container.querySelector('.search-standar-lama').addEventListener('input', (e) => this.renderLama(e.target.value));
            container.querySelector('.search-standar-baru').addEventListener('input', (e) => this.renderBaru(e.target.value));
            
            container.querySelector('.btn-check-all-lama').addEventListener('click', () => {
                this.allLama.forEach(d => { if(!d.already_duplicated) this.selectedLama.add(d.dok_id); });
                this.renderLama();
            });
            container.querySelector('.btn-uncheck-all-lama').addEventListener('click', () => {
                this.selectedLama.clear();
                this.renderLama();
            });
            
            container.querySelector('.btn-check-all-baru').addEventListener('click', () => {
                this.allBaru.forEach(d => this.selectedBaru.add(d.dok_id));
                this.renderBaru();
            });
            container.querySelector('.btn-uncheck-all-baru').addEventListener('click', () => {
                this.selectedBaru.clear();
                this.renderBaru();
            });

            document.getElementById(`btn-duplikasi-${this.typeId}`).addEventListener('click', () => this.executeDuplikasi());
            container.querySelector('.btn-hapus-bulk').addEventListener('click', (e) => {
                if(!e.target.classList.contains('disabled')) this.executeDeleteBulk();
            });
        }

        loadData() {
            axios.get('{{ url("pemutu/peningkatan") }}/' + this.periodeEncId + '/standar-list', {
                params: { target_periode: this.targetPeriode }
            }).then(res => {
                this.allLama = res.data.data.standar_lama;
                this.allBaru = res.data.data.standar_baru;
                this.renderLama();
                this.renderBaru();
            });
        }

        renderLama(search = '') {
            const container = document.getElementById(`list-standar-lama-${this.typeId}`);
            container.innerHTML = '';
            const filtered = search ? this.allLama.filter(d => d.judul.toLowerCase().includes(search.toLowerCase())) : this.allLama;
            
            const list = document.createElement('div');
            list.className = 'list-group list-group-flush';
            filtered.forEach(d => {
                const item = document.createElement('div');
                item.className = 'list-group-item px-3 py-2 d-flex align-items-center';
                const isDuplicated = d.already_duplicated;
                item.innerHTML = `
                    <input type="checkbox" class="form-check-input me-3" value="${d.dok_id}" ${this.selectedLama.has(d.dok_id) ? 'checked' : ''} ${isDuplicated ? 'disabled' : ''}>
                    <div class="flex-fill ${isDuplicated ? 'text-muted' : ''}">
                        <div class="fw-medium small">${d.judul}</div>
                        ${d.kode ? `<div class="extra-small text-muted">${d.kode}</div>` : ''}
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-blue-lt">${d.indikator_count} Ind.</span>
                        ${isDuplicated ? '<span class="badge bg-green-lt ms-1">OK</span>' : ''}
                    </div>
                `;
                if(!isDuplicated) {
                    item.querySelector('input').addEventListener('change', (e) => {
                        if(e.target.checked) this.selectedLama.add(d.dok_id);
                        else this.selectedLama.delete(d.dok_id);
                        this.updateButtons();
                    });
                }
                list.appendChild(item);
            });
            container.appendChild(list);
            this.updateButtons();
        }

        renderBaru(search = '') {
            const container = document.getElementById(`list-standar-baru-${this.typeId}`);
            container.innerHTML = '';
            const filtered = search ? this.allBaru.filter(d => d.judul.toLowerCase().includes(search.toLowerCase())) : this.allBaru;
            
            const list = document.createElement('div');
            list.className = 'list-group list-group-flush';
            filtered.forEach(d => {
                const item = document.createElement('div');
                item.className = 'list-group-item px-3 py-2 d-flex align-items-center';
                item.innerHTML = `
                    <input type="checkbox" class="form-check-input me-3" value="${d.dok_id}" ${this.selectedBaru.has(d.dok_id) ? 'checked' : ''}>
                    <div class="flex-fill">
                        <div class="fw-medium small">${d.judul}</div>
                        ${d.kode ? `<div class="extra-small text-muted">${d.kode}</div>` : ''}
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-green-lt">${d.indikator_count} Ind.</span>
                        <a href="javascript:void(0)" class="btn btn-ghost-danger btn-icon btn-sm ms-2 btn-hapus-single" data-id="${d.dok_id}"><i class="ti ti-trash"></i></a>
                    </div>
                `;
                item.querySelector('input').addEventListener('change', (e) => {
                    if(e.target.checked) this.selectedBaru.add(d.dok_id);
                    else this.selectedBaru.delete(d.dok_id);
                    this.updateButtons();
                });
                item.querySelector('.btn-hapus-single').addEventListener('click', () => this.executeDeleteSingle(d.dok_id, d.judul));
                list.appendChild(item);
            });
            container.appendChild(list);
            this.updateButtons();
        }

        updateButtons() {
            const btnDup = document.getElementById(`btn-duplikasi-${this.typeId}`);
            const btnHapus = document.querySelector(`.panel-standar-container[data-type="${this.typeId}"] .btn-hapus-bulk`);
            const status = document.getElementById(`duplikasi-status-${this.typeId}`);
            
            btnDup.disabled = this.selectedLama.size === 0;
            btnDup.innerHTML = `<i class="ti ti-copy me-1"></i> Duplikasi (${this.selectedLama.size})`;
            status.textContent = this.selectedLama.size > 0 ? `${this.selectedLama.size} dipilih` : '';
            
            if(this.selectedBaru.size > 0) {
                btnHapus.classList.remove('disabled');
                btnHapus.innerHTML = `<i class="ti ti-trash me-2 text-red"></i>Hapus Terpilih (${this.selectedBaru.size})`;
            } else {
                btnHapus.classList.add('disabled');
                btnHapus.innerHTML = `<i class="ti ti-trash me-2 text-red"></i>Hapus Terpilih`;
            }
        }

        executeDuplikasi() {
            Swal.fire({
                title: 'Duplikasi Standar',
                text: `Duplikasi ${this.selectedLama.size} standar terpilih ke periode ${this.targetPeriode}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Duplikasi!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('{{ url("pemutu/peningkatan") }}/' + this.periodeEncId + '/duplikasi', {
                        target_periode: this.targetPeriode,
                        selected_dok_ids: Array.from(this.selectedLama)
                    }).then(res => {
                        window.location.reload();
                    }).catch(err => {
                        showErrorMessage('Error', 'Gagal melakukan duplikasi.');
                    });
                }
            });
        }

        executeDeleteBulk() {
            Swal.fire({
                title: 'Hapus Standar',
                text: `Hapus ${this.selectedBaru.size} standar terpilih dari periode ${this.targetPeriode}? Seluruh indikator di dalamnya akan ikut terhapus!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('{{ url("pemutu/peningkatan") }}/' + this.periodeEncId + '/standar-bulk', {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}',
                        target_periode: this.targetPeriode,
                        selected_dok_ids: Array.from(this.selectedBaru)
                    }).then(res => {
                        window.location.reload();
                    });
                }
            });
        }

        executeDeleteSingle(dokId, judul) {
            Swal.fire({
                title: 'Hapus Standar',
                text: `Hapus standar "${judul}"? Seluruh indikator di dalamnya akan ikut terhapus!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('{{ url("pemutu/peningkatan") }}/' + this.periodeEncId + '/standar/' + dokId, {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}',
                        target_periode: this.targetPeriode
                    }).then(res => {
                        window.location.reload();
                    });
                }
            });
        }
    }

    document.querySelectorAll('.panel-standar-container').forEach(el => {
        const typeId = el.dataset.type;
        const periodeEncId = el.dataset.periodeId;
        managers[typeId] = new DuplikasiManager(typeId, periodeEncId);
    });
});
</script>
@endpush
@endonce
