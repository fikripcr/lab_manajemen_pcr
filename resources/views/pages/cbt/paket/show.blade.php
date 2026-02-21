@extends('layouts.tabler.app')

@section('title', 'Komposisi Paket: ' . $paket->nama_paket)

@section('header')
<x-tabler.page-header title="Komposisi Paket: {{ $paket->nama_paket }}" pretitle="CBT Engine">
    <x-slot:actions>
        <x-tabler.button href="{{ route('cbt.paket.index') }}" class="btn-outline-secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">

    {{-- ===== DAFTAR SOAL TERPILIH ===== --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Daftar Soal Terpilih</h3>
                    <div class="card-subtitle text-muted">{{ $paket->komposisi->count() }} soal dalam paket ini</div>
                </div>
                <div class="card-actions">
                    <span class="badge bg-blue-lt fs-6">{{ $paket->komposisi->count() }} Soal</span>
                </div>
            </div>

            <div class="card-body p-0">
                @if($paket->komposisi->isEmpty())
                    <x-tabler.empty-state
                        title="Belum Ada Soal"
                        text="Pilih soal dari Bank Soal Tersedia di sebelah kanan, lalu klik Tambahkan ke Paket."
                        icon="ti ti-notes-off"
                    />
                @else
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th class="w-1">No</th>
                                    <th>Mata Uji</th>
                                    <th>Pertanyaan</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paket->komposisi as $index => $item)
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-blue-lt">{{ $item->soal->mataUji->nama_mata_uji ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="text-body">{!! strip_tags(substr($item->soal->konten_pertanyaan, 0, 120)) !!}...</div>
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-icon btn-sm btn-outline-danger ajax-delete"
                                            data-url="{{ route('cbt.paket.remove-soal', [$paket->encrypted_paket_ujian_id, $item->encrypted_komposisi_paket_id]) }}"
                                            data-title="Hapus soal dari paket?"
                                            data-text="Soal hanya dihapus dari paket ini, tidak dari Bank Soal."
                                            title="Hapus dari paket">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== BANK SOAL TERSEDIA ===== --}}
    <div class="col-lg-5">
        <div class="card" style="position: sticky; top: 16px;">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Bank Soal Tersedia</h3>
                    <div class="card-subtitle text-muted" id="soal-count-label">{{ $soalTersedia->count() }} soal tersedia</div>
                </div>
            </div>

            <form id="form-add-soal"
                  action="{{ route('cbt.paket.add-soal', $paket->encrypted_paket_ujian_id) }}"
                  method="POST"
                  class="ajax-form"
                  data-redirect="true">
                @csrf

                {{-- Search & Filter --}}
                <div class="card-body border-bottom pb-3">

                    {{-- Search --}}
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                        <input type="text" id="search-soal" class="form-control" placeholder="Cari soal...">
                    </div>

                    {{-- Filter Mata Uji --}}
                    <div class="row g-2">
                        <div class="col">
                            <select id="filter-mata-uji" class="form-select form-select-sm">
                                <option value="">Semua Mata Uji</option>
                                @foreach($soalTersedia->pluck('mataUji')->filter()->unique('mata_uji_id') as $mu)
                                    <option value="{{ $mu->nama_mata_uji }}">{{ $mu->nama_mata_uji }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="button" id="btn-select-all" class="btn btn-sm btn-outline-primary" onclick="selectAllVisible()">
                                <i class="ti ti-checks me-1"></i>Semua
                            </button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAll()">
                                <i class="ti ti-x me-1"></i>Reset
                            </button>
                        </div>
                    </div>

                    {{-- Selected count --}}
                    <div class="mt-2" id="selected-info" style="display:none;">
                        <span class="badge bg-green-lt fs-6" id="selected-count-badge">0 soal dipilih</span>
                    </div>
                </div>

                {{-- List Soal --}}
                <div class="card-body p-0">
                    <div id="soal-list" style="max-height: 420px; overflow-y: auto;">
                        @forelse($soalTersedia as $soal)
                        <label class="soal-item list-group-item list-group-item-action px-3 py-2 border-bottom"
                               data-mata-uji="{{ $soal->mataUji->nama_mata_uji ?? '' }}"
                               data-text="{{ strtolower(strip_tags($soal->konten_pertanyaan)) }}"
                               style="cursor:pointer;">
                            <div class="row align-items-start g-2">
                                <div class="col-auto pt-1">
                                    <input class="form-check-input soal-checkbox"
                                           type="checkbox"
                                           name="soal_ids[]"
                                           value="{{ $soal->encrypted_soal_id }}"
                                           onchange="updateSelectedCount()">
                                </div>
                                <div class="col">
                                    <span class="badge bg-blue-lt mb-1">{{ $soal->mataUji->nama_mata_uji ?? '-' }}</span>
                                    <div class="text-muted small lh-sm">
                                        {!! strip_tags(substr($soal->konten_pertanyaan, 0, 150)) !!}...
                                    </div>
                                </div>
                            </div>
                        </label>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="ti ti-mood-empty d-block" style="font-size:2rem"></i>
                            Semua soal sudah ada di paket ini
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Submit --}}
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary w-100" id="btn-tambahkan">
                        <i class="ti ti-plus me-2"></i>Tambahkan ke Paket
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// ── Search & Filter ──────────────────────────────────
const searchInput   = document.getElementById('search-soal');
const filterSelect  = document.getElementById('filter-mata-uji');
const soalItems     = document.querySelectorAll('.soal-item');

function filterSoal() {
    const term    = searchInput.value.toLowerCase();
    const mataUji = filterSelect.value.toLowerCase();
    let visible   = 0;

    soalItems.forEach(item => {
        const text = item.dataset.text || '';
        const mu   = (item.dataset.mataUji || '').toLowerCase();
        const matchText = !term    || text.includes(term);
        const matchMU   = !mataUji || mu === mataUji;
        const show = matchText && matchMU;
        item.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('soal-count-label').textContent = visible + ' soal ditampilkan';
}

searchInput.addEventListener('input', filterSoal);
filterSelect.addEventListener('change', filterSoal);

// ── Select All / Clear ───────────────────────────────
function selectAllVisible() {
    soalItems.forEach(item => {
        if (item.style.display !== 'none') {
            const cb = item.querySelector('.soal-checkbox');
            if (cb) cb.checked = true;
        }
    });
    updateSelectedCount();
}

function clearAll() {
    document.querySelectorAll('.soal-checkbox').forEach(cb => cb.checked = false);
    updateSelectedCount();
}

// ── Selected Count Badge ─────────────────────────────
function updateSelectedCount() {
    const count  = document.querySelectorAll('.soal-checkbox:checked').length;
    const badge  = document.getElementById('selected-count-badge');
    const info   = document.getElementById('selected-info');
    const btn    = document.getElementById('btn-tambahkan');
    badge.textContent = count + ' soal dipilih';
    info.style.display = count > 0 ? '' : 'none';
    btn.disabled = (count === 0);
}

// Disable submit by default
document.addEventListener('DOMContentLoaded', () => updateSelectedCount());
</script>
@endpush
