@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
    <x-tabler.page-header :title="$lab->name" pretitle="Laboratorium">
        <x-slot:actions>
            <x-tabler.button type="edit" :href="route('lab.labs.edit', $lab->encrypted_lab_id)" />
            <x-tabler.button type="back" :href="route('lab.labs.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row row-cards">
        <!-- Top Stats Row -->
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-primary text-white avatar">
                                <i class="ti ti-users"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">Kapasitas</div>
                            <div class="text-muted">{{ $lab->capacity }} Orang</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-green text-white avatar">
                                <i class="ti ti-package"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">Inventaris</div>
                            <div class="text-muted">{{ $lab->labInventaris->count() }} Item</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-yellow text-white avatar">
                                <i class="ti ti-calendar"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">Jadwal Aktif</div>
                            <div class="text-muted">{{ $lab->jadwals->count() }} Sesi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-blue text-white avatar">
                                <i class="ti ti-user-shield"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">Tim Lab</div>
                            <div class="text-muted">{{ $lab->labTeams->count() }} Anggota</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content (Left) -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Laboratorium</h3>
                    <div class="card-actions">
                        <span class="badge bg-muted-lt"><i class="ti ti-map-pin me-1"></i> {{ $lab->location }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="markdown">
                        {!! $lab->description ?: '<em class="text-muted">Tidak ada deskripsi.</em>' !!}
                    </div>

                    <!-- Gallery Section -->
                    @if ($lab->getMedia('lab_images')->count() > 0)
                        <div class="mt-4 border-top pt-3">
                            <h4 class="card-title mb-3">Galeri</h4>
                            <div class="row g-2">
                                @foreach ($lab->getMedia('lab_images') as $media)
                                    <div class="col-6 col-md-3">
                                        <a href="{{ $media->getUrl() }}" target="_blank" class="d-block shadow-sm rounded overflow-hidden">
                                            <img src="{{ $media->getUrl() }}" class="img-fluid" alt="{{ $media->name }}" style="height: 120px; width: 100%; object-fit: cover; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Attachments Section -->
                    @if ($lab->getMedia('lab_attachments')->count() > 0)
                        <div class="mt-4 border-top pt-3">
                            <h4 class="card-title mb-3">Dokumen</h4>
                            <div class="list-group list-group-flush border rounded-2">
                                @foreach ($lab->getMedia('lab_attachments') as $media)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-xs rounded bg-red-lt me-2">PDF</span>
                                            <div>
                                                <div class="text-truncate" style="max-width: 300px;">{{ $media->name }}</div>
                                                <div class="text-muted small">{{ round($media->size / 1024, 2) }} KB</div>
                                            </div>
                                        </div>
                                        <x-tabler.button :href="$media->getUrl()" target="_blank" class="btn-icon btn-sm btn-ghost-primary" title="Download" icon="ti ti-download" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Content (Right) -->
        <div class="col-lg-4">
            <!-- Schedule Widget -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Jadwal Mendatang</h3>
                </div>
                <div class="card-body card-body-scrollable card-body-scrollable-shadow" style="max-height: 300px;">
                    <div class="divide-y">
                        @forelse ($lab->jadwals->sortBy('hari_id')->take(5) as $jadwal)
                            <div>
                                <div class="row">
                                    <div class="col-auto">
                                        <span class="avatar bg-blue-lt">{{ substr($jadwal->hari, 0, 3) }}</span>
                                    </div>
                                    <div class="col">
                                        <div class="text-truncate">
                                            <strong>{{ $jadwal->mataKuliah->nama_mk ?? 'N/A' }}</strong>
                                        </div>
                                        <div class="text-muted small">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</div>
                                        <div class="text-muted small">{{ $jadwal->dosen_pengampu }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted small py-3">Tidak ada jadwal.</div>
                        @endforelse
                    </div>
                </div>
                @if($lab->jadwals->count() > 5)
                    <div class="card-footer p-2 text-center">
                        <a href="{{ route('jadwal.index') }}" class="small">Lihat Semua</a>
                    </div>
                @endif
            </div>

            <!-- Team Widget -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Tim Aktif</h3>
                    <div class="card-actions btn-group">
                        <x-tabler.button type="button" class="btn-outline-primary btn-sm" href="{{ route('lab.labs.teams.index', $lab->encrypted_lab_id) }}" text="Manage" />
                        <x-tabler.button type="button" class="btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTeamAdd" icon="ti ti-plus" />
                    </div>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($lab->getActiveTeamMembers()->take(5) as $teamMember)
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm bg-secondary-lt rounded-circle">
                                        {{ substr($teamMember->user->name, 0, 2) }}
                                    </span>
                                </div>
                                <div class="col text-truncate">
                                    <a href="#" class="text-reset d-block">{{ $teamMember->user->name }}</a>
                                    <div class="d-block text-muted text-truncate mt-n1 small">{{ $teamMember->jabatan }}</div>
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-green-lt"></span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted small">
                            Belum ada tim.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Inventaris Widget -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Inventaris Terbaru</h3>
                    <div class="card-actions btn-group">
                        <x-tabler.button type="button" class="btn-outline-primary btn-sm" href="{{ route('lab.labs.inventaris.index', $lab->encrypted_lab_id) }}" text="Manage" />
                        <x-tabler.button type="button" class="btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalInventarisAdd" icon="ti ti-plus" />
                    </div>
                </div>
                <div class="list-group list-group-flush">
                    @forelse ($lab->labInventaris()->with('inventaris')->orderBy('tanggal_penempatan', 'desc')->take(5)->get() as $item)
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-sm bg-blue-lt">
                                        <i class="ti ti-package"></i>
                                    </span>
                                </div>
                                <div class="col text-truncate">
                                    <a href="#" class="text-reset d-block">{{ $item->inventaris->nama_alat ?? 'Unknown' }}</a>
                                    <div class="d-block text-muted text-truncate mt-n1 small">{{ $item->kode_inventaris }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted small">
                            Belum ada inventaris.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Actions -->
    <div class="mt-4 pt-3 border-top d-flex justify-content-between">
        <div>
            <x-tabler.button type="delete" 
                        class="ajax-delete"
                        :data-url="route('lab.labs.destroy', $lab->encrypted_lab_id)"
                        data-title="Hapus Lab"
                        data-text="Yakin ingin menghapus lab ini? Data inventaris dan jadwal terkait akan ikut terhapus."
                        data-redirect="{{ route('lab.labs.index') }}" />
        </div>
        <div class="text-muted small align-self-center">
            Last updated: {{ $lab->updated_at->diffForHumans() }}
        </div>
    </div>
@endsection

@push('modals')
    <!-- Modal Add Team -->
    <x-tabler.form-modal
        id="modalTeamAdd"
        id_form="formTeamAdd"
        title="Tambah Anggota Tim"
        route="{{ route('lab.labs.teams.store', $lab->encrypted_lab_id) }}"
        method="POST"
        size="modal-lg"
        submitText="Simpan"
        class="lab-assignment-form"
    >
        <x-tabler.form-select class="select2-user" name="user_id" label="Pilih User" required="true" style="width: 100%;" />
        <div class="mb-3">
            <x-tabler.form-input name="jabatan" label="Jabatan" placeholder="Misal: Teknisi" />
        </div>
        <div class="mb-3">
            <x-tabler.form-input type="date" name="tanggal_mulai" label="Tanggal Mulai" value="{{ date('Y-m-d') }}" />
        </div>
    </x-tabler.form-modal>

    <!-- Modal Add Inventaris -->
    <x-tabler.form-modal
        id="modalInventarisAdd"
        id_form="formInventarisAdd"
        title="Tambah Inventaris"
        route="{{ route('lab.labs.inventaris.store', $lab->encrypted_lab_id) }}"
        method="POST"
        size="modal-lg"
        submitText="Simpan"
        class="lab-assignment-form"
    >
        <x-tabler.form-select class="select2-inventaris" name="inventaris_id" label="Nama Alat" required="true" style="width: 100%;" />
        <div class="mb-3">
            <x-tabler.form-input name="no_series" label="Nomor Seri" required="true" />
        </div>
        <div class="mb-3">
            <x-tabler.form-input type="date" name="tanggal_penempatan" label="Tanggal Penempatan" value="{{ date('Y-m-d') }}" required="true" />
        </div>
        <x-tabler.form-select name="status" label="Status" value="active">
            <option value="active" selected>Active</option>
            <option value="moved">Moved</option>
            <option value="inactive">Inactive</option>
        </x-tabler.form-select>
        <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="3" />
    </x-tabler.form-modal>
@endpush

@push('scripts')
    <!-- Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fix Select2 in Bootstrap Modal
            $.fn.select2.defaults.set( "theme", "bootstrap-5" );

            // Init Select2 for User
            $('#modalTeamAdd').on('shown.bs.modal', function () {
                $('.select2-user').select2({
                    dropdownParent: $('#modalTeamAdd'),
                    placeholder: 'Cari User...',
                    ajax: {
                        url: '{{ route("lab.labs.teams.get-users", $lab->encrypted_lab_id) }}',
                        dataType: 'json',
                        delay: 250,
                        data: params => ({ search: params.term }),
                        processResults: data => ({
                            results: (data.results || data).map(item => ({ id: item.id, text: item.text }))
                        }),
                        cache: true
                    }
                });
            });

             // Init Select2 for Inventaris
             $('#modalInventarisAdd').on('shown.bs.modal', function () {
                $('.select2-inventaris').select2({
                    dropdownParent: $('#modalInventarisAdd'),
                    placeholder: 'Cari Inventaris...',
                    ajax: {
                        url: '{{ route("lab.labs.inventaris.get-inventaris", $lab->encrypted_lab_id) }}',
                        dataType: 'json',
                        delay: 250,
                        data: params => ({ search: params.term }),
                        processResults: data => ({
                            results: (data.results || data).map(item => ({ id: item.id, text: item.text }))
                        }),
                        cache: true
                    }
                });
            });

            // Handle Custom Form Submit
            $('.lab-assignment-form').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const $btn = $form.find('button[type="submit"]');
                const originalText = $btn.html();

                $btn.prop('disabled', true).html('Proses...');
                
                // Clear errors
                $form.find('.is-invalid').removeClass('is-invalid');
                $form.find('.invalid-feedback').remove();

                const formData = new FormData(this);

                axios.post($form.attr('action'), formData)
                    .then(response => {
                        // Show success (you can use your Toast/Swal here)
                        // Assuming window.Swal is available from admin.js
                        if(window.Swal) {
                            window.Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.data.message || 'Data berhasil disimpan',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            alert(response.data.message);
                             window.location.reload();
                        }
                    })
                    .catch(error => {
                        $btn.prop('disabled', false).html(originalText);
                        console.error(error);
                        let msg = "Terjadi kesalahan";
                        if(error.response && error.response.data && error.response.data.message) {
                             msg = error.response.data.message;
                             // Display validation errors if any
                             if(error.response.data.errors) {
                                  // Simplistic error display for now
                                  msg += "\n" + Object.values(error.response.data.errors).flat().join("\n");
                             }
                        }
                         if(window.Swal) {
                            window.Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: msg
                            });
                        } else {
                            alert(msg);
                        }
                    });
            });
        });
    </script>
@endpush
@endsection
