@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="{{ $survei->judul }}" pretitle="Form Builder">
    <x-slot:actions>
        <x-tabler.button type="back" href="{{ route('survei.index') }}" />
        <x-tabler.button type="submit" href="{{ route('survei.preview', $survei->id) }}" 
            icon="ti ti-eye" text="Preview" class="btn-outline-primary" target="_blank" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="row g-4">
            <!-- Sidebar: Halaman -->
            <div class="col-md-3">
                <div class="card sticky-top" style="top: 1rem;">
                    <div class="card-header">
                        <h3 class="card-title">Halaman</h3>
                        <div class="card-actions">
                            <button type="button" class="btn btn-icon btn-sm btn-primary" id="btn-add-halaman" title="Tambah Halaman">
                                <i class="ti ti-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="list-group list-group-flush" id="list-halaman">
                        @foreach($survei->halaman as $halaman)
                        <div class="list-group-item list-group-item-action d-flex align-items-center {{ $loop->first ? 'active' : '' }}" 
                             data-id="{{ $halaman->id }}">
                            <i class="ti ti-grip-vertical text-muted me-2" style="cursor: grab;"></i>
                            <span class="halaman-title text-truncate flex-fill" style="cursor: pointer;" onclick="window.selectHalaman && window.selectHalaman({{ $halaman->id }})">{{ $halaman->judul_halaman }}</span>
                            <div class="d-flex align-items-center ms-2">
                                <span class="badge bg-muted me-1">{{ $halaman->pertanyaan->count() }}</span>
                                <div class="dropdown">
                                    <i class="ti ti-dots-vertical cursor-pointer" data-bs-toggle="dropdown"></i>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); window.editHalaman && window.editHalaman({{ $halaman->id }}, '{{ addslashes($halaman->judul_halaman) }}')">
                                            <i class="ti ti-pencil me-2"></i>Rename
                                        </a>
                                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); window.deleteHalaman && window.deleteHalaman({{ $halaman->id }})">
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
                    <div class="halaman-pane {{ $loop->first ? '' : 'd-none' }}" id="halaman-{{ $halaman->id }}">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3 class="card-title">Pertanyaan di <span class="fw-bold halaman-title-display">{{ $halaman->judul_halaman }}</span></h3>
                                <div class="card-actions">
                                    <div class="dropdown me-2">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="ti ti-plus"></i> Tambah
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @foreach(['Teks_Singkat' => 'ti-text-size', 'Esai' => 'ti-align-left', 'Angka' => 'ti-123', 'Pilihan_Ganda' => 'ti-circle-dot', 'Kotak_Centang' => 'ti-checkbox', 'Dropdown' => 'ti-select', 'Skala_Linear' => 'ti-adjustments-horizontal', 'Tanggal' => 'ti-calendar', 'Upload_File' => 'ti-upload'] as $tipe => $icon)
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); addPertanyaan({{ $halaman->id }}, '{{ $tipe }}')">
                                                <i class="ti {{ $icon }} me-2"></i>{{ str_replace('_', ' ', $tipe) }}
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="pertanyaan-list" data-halaman-id="{{ $halaman->id }}">
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
    </div>
</div>

<!-- Modal Edit Halaman -->
<div class="modal modal-blur fade" id="modalEditHalaman" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rename Halaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit-halaman-id">
                <div class="mb-3">
                    <label class="form-label">Judul Halaman</label>
                    <input type="text" class="form-control" id="edit-halaman-judul" placeholder="Judul Halaman">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveHalaman()">Simpan</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // --- Config (safe without jQuery) ---
    const csrfToken = '{{ csrf_token() }}';
    const ROUTES = {
        halamanStore:      '{{ route("survei.halaman.store", $survei->id) }}',
        halamanUpdate:     '{{ route("survei.halaman.update", ":id") }}',
        halamanDestroy:    '{{ route("survei.halaman.destroy", ":id") }}',
        halamanReorder:    '{{ route("survei.halaman.reorder") }}',
        pertanyaanStore:   '{{ route("survei.pertanyaan.store", $survei->id) }}',
        pertanyaanUpdate:  '{{ route("survei.pertanyaan.update", ":id") }}',
        pertanyaanDestroy: '{{ route("survei.pertanyaan.destroy", ":id") }}',
        pertanyaanReorder: '{{ route("survei.pertanyaan.reorder") }}',
    };
    function routeFor(key, id) {
        return ROUTES[key].replace(':id', id);
    }
    let currentHalamanId = {{ $survei->halaman->first()->id ?? 0 }};

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
                window.selectHalaman(parseInt(id));
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

        // --- Expose functions for inline onclick handlers ---
        window.selectHalaman = function(id) {
            currentHalamanId = id;
            window.location.hash = 'halaman-' + id;
            $('#list-halaman .list-group-item').removeClass('active');
            $(`#list-halaman .list-group-item[data-id="${id}"]`).addClass('active');
            $('.halaman-pane').addClass('d-none');
            $('#halaman-' + id).removeClass('d-none');
        };

        window.editHalaman = function(id, currentTitle) {
            $('#edit-halaman-id').val(id);
            $('#edit-halaman-judul').val(currentTitle);
            new bootstrap.Modal('#modalEditHalaman').show();
        };

        window.saveHalaman = function() {
            let id = $('#edit-halaman-id').val();
            let judul = $('#edit-halaman-judul').val();
            $.ajax({
                url: routeFor('halamanUpdate', id),
                type: 'PUT',
                data: { _token: csrfToken, judul_halaman: judul },
                success: function(res) {
                    if (res.success) {
                        $(`#list-halaman .list-group-item[data-id="${id}"] .halaman-title`).text(judul);
                        $(`#halaman-${id} .halaman-title-display`).text(judul);
                        bootstrap.Modal.getInstance('#modalEditHalaman').hide();
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
                }
            });
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
                                $card.slideUp(200, function() {
                                    $(this).remove();
                                    if ($list.children('.card-pertanyaan').length === 0) {
                                        $list.siblings('.empty-state').removeClass('d-none');
                                    }
                                });
                                showSuccessMessage('Pertanyaan berhasil dihapus.');
                            }
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
            window.debounceSave(pertanyaanId);
        };

        window.addOpsi = function(pertanyaanId) {
            const wrapper = $(`.option-list-${pertanyaanId}`);
            const html = `
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text"><i class="ti ti-circle"></i></span>
                    <input type="text" class="form-control" value="Opsi Baru" onchange="debounceSave(${pertanyaanId})">
                    <button class="btn btn-icon btn-ghost-danger" onclick="$(this).parent().remove(); debounceSave(${pertanyaanId});">
                        <i class="ti ti-x"></i>
                    </button>
                </div>`;
            wrapper.append(html);
            window.debounceSave(pertanyaanId);
        };

        window.savePertanyaan = function(id) {
            const card = $(`.card-pertanyaan[data-id="${id}"]`);
            if (!card.length) return;

            card.css('opacity', '0.7');

            let data = {
                _token: csrfToken,
                _method: 'PUT',
                teks_pertanyaan: card.find('.pertanyaan-teks-input').val(),
                tipe: card.find('.pertanyaan-tipe-select').val(),
                wajib_diisi: card.find('.pertanyaan-wajib-check').is(':checked') ? 1 : 0
            };

            let opsi = [];
            card.find(`.option-list-${id} input[type="text"]`).each(function() {
                opsi.push($(this).val());
            });
            if (opsi.length > 0) {
                data.opsi = opsi;
            }

            $.post(routeFor('pertanyaanUpdate', id), data, function(res) {
                if (res.success && res.data && res.data.html) {
                    let $newCard = $(res.data.html);
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
