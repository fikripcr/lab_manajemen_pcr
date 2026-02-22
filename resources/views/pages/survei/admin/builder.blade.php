@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $survei->judul }}" pretitle="Form Builder">
    <x-slot:actions>
        <x-tabler.button type="back" href="{{ route('survei.index') }}" />
        <x-tabler.button type="submit" href="{{ route('survei.preview', $survei->encrypted_survei_id) }}"
            icon="ti ti-eye" text="Preview" class="btn-outline-primary" target="_blank" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        <div class="row g-4">
            <!-- Sidebar: Halaman -->
            <div class="col-md-3">
                <div class="card sticky-top">
                    <div class="card-header">
                        <h3 class="card-title">Halaman</h3>
                        <div class="card-actions">
                            <x-tabler.button type="button" class="btn-icon btn-primary" id="btn-add-halaman" title="Tambah Halaman" icon="ti ti-plus" />
                        </div>
                    </div>
                    <div class="list-group list-group-flush" id="list-halaman">
                        @foreach($survei->halaman as $halaman)
                        <div class="list-group-item list-group-item-action d-flex align-items-center {{ $loop->first ? 'active' : '' }}"
                             data-id="{{ $halaman->encrypted_halaman_id }}" data-deskripsi="{{ e($halaman->deskripsi_halaman) }}">
                            <i class="ti ti-grip-vertical text-muted me-2" style="cursor: grab;"></i>
                            <span class="halaman-title text-truncate flex-fill" style="cursor: pointer;" onclick="window.selectHalaman && window.selectHalaman('{{ $halaman->encrypted_halaman_id }}')">{{ $halaman->judul_halaman }}</span>
                            <div class="d-flex align-items-center ms-2">
                                <span class="badge bg-muted me-1">{{ $halaman->pertanyaan->count() }}</span>
                                <div class="dropdown">
                                    <i class="ti ti-dots-vertical cursor-pointer" data-bs-toggle="dropdown"></i>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); window.editHalaman && window.editHalaman('{{ $halaman->encrypted_halaman_id }}')">
                                            <i class="ti ti-pencil me-2"></i>Edit Halaman
                                        </a>
                                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); window.deleteHalaman && window.deleteHalaman('{{ $halaman->encrypted_halaman_id }}')">
                                            <i class="ti ti-trash me-2"></i>Hapus
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Content: Pertanyaan -->
            <div class="col-md-9">
                <div id="halaman-content-wrapper">
                    @foreach($survei->halaman as $halaman)
                    <div class="halaman-pane {{ $loop->first ? '' : 'd-none' }}" id="halaman-{{ $halaman->encrypted_halaman_id }}">
                        <div class="card mb-3">
                            <div class="card-header">
                                <div>
                                    <h3 class="card-title mb-0">Pertanyaan di <span class="fw-bold halaman-title-display">{{ $halaman->judul_halaman }}</span></h3>
                                    @if($halaman->deskripsi_halaman)
                                    <div class="text-muted small mt-1 halaman-deskripsi-display">{!! $halaman->deskripsi_halaman !!}</div>
                                    @else
                                    <div class="text-muted small mt-1 halaman-deskripsi-display"></div>
                                    @endif
                                </div>
                                <div class="card-actions">
                                    <div class="dropdown">
                                        <a href="#" class="badge bg-primary-lt text-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-plus me-1"></i>Tambah
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @foreach(['Teks_Singkat' => 'ti-text-size', 'Esai' => 'ti-align-left', 'Angka' => 'ti-123', 'Pilihan_Ganda' => 'ti-circle-dot', 'Kotak_Centang' => 'ti-checkbox', 'Dropdown' => 'ti-select', 'Skala_Linear' => 'ti-adjustments-horizontal', 'Tanggal' => 'ti-calendar', 'Upload_File' => 'ti-upload'] as $tipe => $icon)
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); addPertanyaan('{{ $halaman->encrypted_halaman_id }}', '{{ $tipe }}')">
                                                <i class="ti {{ $icon }} me-2"></i>{{ str_replace('_', ' ', $tipe) }}
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="pertanyaan-list" data-halaman-id="{{ $halaman->encrypted_halaman_id }}">
                                    @foreach($halaman->pertanyaan as $pertanyaan)
                                        @include('pages.survei.admin.partials.question_card', ['pertanyaan' => $pertanyaan])
                                    @endforeach
                                </div>
                                <div class="p-3 text-center text-muted empty-state {{ $halaman->pertanyaan->count() > 0 ? 'd-none' : '' }}">
                                    <i class="ti ti-mood-empty" style="font-size:2rem;"></i>
                                    <p class="mt-2 mb-0">Belum ada pertanyaan di halaman ini.</p>
                                    <p class="small">Klik <strong>"Tambah"</strong> untuk menambahkan pertanyaan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
<!-- Modal Edit Halaman -->
<x-tabler.form-modal
    id="modalEditHalaman"
    title="Edit Halaman"
    route="#"
    id_form="formEditHalaman"
>
    <input type="hidden" id="edit-halaman-id">
    <x-tabler.form-input name="judul_halaman" label="Judul Halaman" id="edit-halaman-judul" placeholder="Judul Halaman" />
    <x-tabler.form-textarea name="deskripsi_halaman" label="Keterangan" id="edit-halaman-deskripsi" rows="3" placeholder="Instruksi singkat untuk responden di halaman ini..." />
    
    <x-slot:footer>
        <x-tabler.button type="cancel" data-bs-dismiss="modal" text="Batal" />
        <x-tabler.button type="button" class="ms-auto" onclick="window.saveHalaman()" text="Simpan" />
    </x-slot:footer>
</x-tabler.form-modal>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // --- Config (safe without jQuery) ---
    const csrfToken = '{{ csrf_token() }}';
    const ROUTES = {
        halamanStore:      '{{ route("survei.halaman.store", $survei->encrypted_survei_id) }}',
        halamanUpdate:     '{{ route("survei.halaman.update", ":id") }}',
        halamanDestroy:    '{{ route("survei.halaman.destroy", ":id") }}',
        halamanReorder:    '{{ route("survei.halaman.reorder") }}',
        pertanyaanStore:   '{{ route("survei.pertanyaan.store", $survei->encrypted_survei_id) }}',
        pertanyaanUpdate:  '{{ route("survei.pertanyaan.update", ":id") }}',
        pertanyaanDestroy: '{{ route("survei.pertanyaan.destroy", ":id") }}',
        pertanyaanReorder: '{{ route("survei.pertanyaan.reorder") }}',
    };
    function routeFor(key, id) {
        return ROUTES[key].replace(':id', id);
    }
    let currentHalamanId = '{{ $survei->halaman->first()->encrypted_halaman_id ?? '' }}' || null;

    // --- Global Functions (must be outside onReady for onclick handlers) ---
    window.addPertanyaan = function(halamanId, tipe) {
        tipe = tipe || 'Teks_Singkat';
        
        $.post(ROUTES.pertanyaanStore, {
            _token: csrfToken,
            halaman_id: halamanId,
            tipe: tipe,
            teks_pertanyaan: 'Pertanyaan Baru'
        }, function(res) {
            if (res.success) {
                let $list = $(`#halaman-${halamanId} .pertanyaan-list`);
                $list.append(res.data.html);
                $(`#halaman-${halamanId} .empty-state`).addClass('d-none');
                updateHalamanCount(halamanId);
                showSuccessMessage('Pertanyaan berhasil ditambahkan.');
                if (typeof window.initOfflineSelect2 === 'function') {
                    window.initOfflineSelect2();
                }
            } else {
                showErrorMessage('Error', res.message || 'Gagal menambahkan pertanyaan.');
            }
        }).fail(function(xhr) {
            let errorMsg = 'Terjadi kesalahan saat menambahkan pertanyaan.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            showErrorMessage('Error', errorMsg);
        });
    };

    window.updateHalamanCount = function(halamanId) {
        const $listGroupItem = $(`#list-halaman .list-group-item[data-id="${halamanId}"]`);
        const questionCount = $listGroupItem.closest('.halaman-pane').find('.card-pertanyaan').length;
        $listGroupItem.find('.badge').text(questionCount);
    };

    window.deletePertanyaan = function(id) {
        Swal.fire({
            title: 'Hapus pertanyaan ini?',
            text: 'Pertanyaan akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d63939',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: routeFor('pertanyaanDestroy', id),
                    type: 'DELETE',
                    data: { _token: csrfToken },
                    success: function(res) {
                        if (res.success) {
                            let $card = $(`.card-pertanyaan[data-id="${id}"]`);
                            let $list = $card.closest('.pertanyaan-list');
                            $card.remove();
                            if ($list.children('.card-pertanyaan').length === 0) {
                                $list.closest('.card').find('.empty-state').removeClass('d-none');
                            }
                            updateHalamanCount(currentHalamanId);
                            showSuccessMessage('Pertanyaan dihapus.');
                        } else {
                            showErrorMessage('Error', res.message);
                        }
                    }
                });
            }
        });
    };

    window.savePertanyaan = null; // will be defined inside onReady with full logic

    // selectHalaman MUST be global before jQuery loads (used in inline onclick)
    window.selectHalaman = function(id) {
        currentHalamanId = id;
        window.location.hash = 'halaman-' + id;
        // Vanilla JS DOM operations (no jQuery dependency)
        document.querySelectorAll('#list-halaman .list-group-item').forEach(function(el) {
            el.classList.remove('active');
        });
        const activeItem = document.querySelector(`#list-halaman .list-group-item[data-id="${id}"]`);
        if (activeItem) activeItem.classList.add('active');
        document.querySelectorAll('.halaman-pane').forEach(function(el) {
            el.classList.add('d-none');
        });
        const targetPane = document.getElementById('halaman-' + id);
        if (targetPane) targetPane.classList.remove('d-none');
    };

    // --- Wait for jQuery to be ready (Vite defers module execution) ---
    function onReady(fn) {
        if (typeof window.$ !== 'undefined') { fn(); }
        else { document.addEventListener('DOMContentLoaded', fn); }
    }
    
    onReady(function() {
        // --- Hash Persistence (survive reload) ---
        let hash = window.location.hash;
        if (hash && hash.startsWith('#halaman-')) {
            let id = hash.replace('#halaman-', '');
            if ($('#halaman-' + id).length) {
                window.selectHalaman(id);
            }
        }

        // --- Sortable Halaman ---
        let listHalaman = document.getElementById('list-halaman');
        if (listHalaman) {
            new Sortable(listHalaman, {
                animation: 150,
                handle: '.ti-grip-vertical',
                ghostClass: 'bg-primary-lt',
                onEnd: function () {
                    let order = [];
                    $('#list-halaman .list-group-item').each(function() {
                        order.push($(this).data('id'));
                    });
                    $.post(ROUTES.halamanReorder, { _token: csrfToken, order: order });
                }
            });
        }

        // --- Sortable Pertanyaan ---
        function renumberPertanyaan(listEl) {
            $(listEl).find('.card-pertanyaan').each(function(idx) {
                const num = idx + 1;
                $(this).find('.question-number-badge').text('Soal #' + num);
                $(this).find('.edit-view-title').text('Edit Soal #' + num);
            });
        }

        function initSortable(el) {
            new Sortable(el, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'bg-primary-lt',
                onEnd: function (evt) {
                    let order = [];
                    $(evt.to).find('.card-pertanyaan').each(function() {
                        order.push($(this).data('id'));
                    });
                    $.post(ROUTES.pertanyaanReorder, { _token: csrfToken, order: order });
                    renumberPertanyaan(evt.to);
                }
            });
        }
        document.querySelectorAll('.pertanyaan-list').forEach(initSortable);

        // --- Halaman Actions ---
        $('#btn-add-halaman').click(function() {
            let $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span>');
            $.post(ROUTES.halamanStore, { _token: csrfToken }, function(res) {
                if (res.success) location.reload();
            }).fail(function() {
                $btn.prop('disabled', false).html('<i class="ti ti-plus"></i>');
            });
        });

        // selectHalaman already declared globally above (no jQuery needed for it)

        window.editHalaman = function(id) {
            const $item = $(`#list-halaman .list-group-item[data-id="${id}"]`);
            const currentTitle = $item.find('.halaman-title').text().trim();
            const currentDesc = $item.data('deskripsi') || '';

            $('#edit-halaman-id').val(id);
            $('#edit-halaman-judul').val(currentTitle);
            $('#edit-halaman-deskripsi').val(currentDesc);

            let modalEl = document.getElementById('modalEditHalaman');
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (!modal) {
                modal = new bootstrap.Modal(modalEl);
            }
            modal.show();
        };

        window.saveHalaman = function() {
            let id = $('#edit-halaman-id').val();
            let judul = $('#edit-halaman-judul').val();
            let deskripsi = $('#edit-halaman-deskripsi').val().trim();

            $.ajax({
                url: routeFor('halamanUpdate', id),
                type: 'PUT',
                data: { _token: csrfToken, judul_halaman: judul, deskripsi_halaman: deskripsi },
                success: function(res) {
                    if (res.success) {
                        $(`#list-halaman .list-group-item[data-id="${id}"] .halaman-title`).text(judul);
                        $(`#list-halaman .list-group-item[data-id="${id}"]`).data('deskripsi', deskripsi);
                        $(`#halaman-${id} .halaman-title-display`).text(judul);
                        $(`#halaman-${id} .halaman-deskripsi-display`).html(deskripsi);
                        
                        let modalEl = document.getElementById('modalEditHalaman');
                        let modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) {
                            modal.hide();
                        }
                        
                        showSuccessMessage('Halaman berhasil diperbarui.');
                    }
                }
            });
        };

        window.deleteHalaman = function(id) {
            Swal.fire({
                title: 'Hapus halaman ini?',
                text: 'Semua pertanyaan di halaman ini juga akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d63939',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: routeFor('halamanDestroy', id),
                        type: 'DELETE',
                        data: { _token: csrfToken },
                        success: function(res) {
                            if (res.success) location.reload();
                            else showErrorMessage('Gagal', res.message);
                        }
                    });
                }
            });
        };

        let debounceTimers = {};
        window.debounceSave = function(id) {
            clearTimeout(debounceTimers[id]);
            debounceTimers[id] = setTimeout(() => window.savePertanyaan(id), 800);
        };

        window.onTypeChange = function(pertanyaanId, selectEl) {
            window.savePertanyaan(pertanyaanId, true);
        };

        window.addOpsi = function(pertanyaanId) {
            const wrapper = $(`.option-list-${pertanyaanId}`);
            const html = `
                <div class="input-group input-group-sm mb-1 opsi-item border-0">
                    <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-circle"></i></span>
                    <input type="text" class="form-control opsi-label shadow-none border-start-0" value="Opsi Baru" onchange="debounceSave(${pertanyaanId})">
                    <button class="btn btn-icon btn-ghost-danger" onclick="$(this).closest('.opsi-item').remove(); debounceSave(${pertanyaanId});">
                        <i class="ti ti-x"></i>
                    </button>
                </div>`;
            wrapper.append(html);
            window.debounceSave(pertanyaanId);
        };

        window.savePertanyaan = function(id, keepEdit = false) {
            const card = $(`.card-pertanyaan[data-id="${id}"]`);
            if (!card.length) return;

            card.css('opacity', '0.7');

            let data = {
                _token: csrfToken,
                _method: 'PUT',
                teks_pertanyaan: card.find('.pertanyaan-teks-input').val(),
                tipe: card.find('.pertanyaan-tipe-select').val(),
                wajib_diisi: card.find('.pertanyaan-wajib-check').is(':checked') ? 1 : 0,
                next_pertanyaan_id: card.find('.pertanyaan-next-select').val()
            };

            let opsi = [];
            card.find(`.option-list-${id} .opsi-item`).each(function() {
                let $item = $(this);
                opsi.push({
                    id: $item.data('id'),
                    label: $item.find('.opsi-label').val(),
                    next_pertanyaan_id: $item.find('.opsi-next-select').val()
                });
            });
            
            if (opsi.length >= 0) { // Always send opsi if card has them
                data.opsi = opsi;
            }

            $.post(routeFor('pertanyaanUpdate', id), data, function(res) {
                if (res.success && res.data && res.data.html) {
                    let $newCard = $(res.data.html);
                    // Only keep edit open if explicitly requested (e.g. type change)
                    if (keepEdit) {
                        $newCard.find(`.static-view-${id}`).addClass('d-none');
                        $newCard.find(`.edit-view-${id}`).removeClass('d-none');
                    }
                    // Always swap the card with fresh HTML
                    card.replaceWith($newCard);
                }
                card.css('opacity', '1');
            }).fail(function() {
                card.css('opacity', '1');
            });
        };

    }); // end DOMContentLoaded
</script>
@endpush
