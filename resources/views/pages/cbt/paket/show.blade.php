@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">CBT Engine</div>
                <h2 class="page-title">Komposisi Paket: {{ $paket->nama_paket }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('cbt.paket.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            {{-- Soal yang sudah ada dalam paket --}}
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Soal Terpilih ({{ $paket->komposisi->count() }})</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mata Uji</th>
                                    <th>Pertanyaan</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paket->komposisi as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-muted">{{ $item->soal->mataUji->nama_mata_uji }}</td>
                                    <td>{!! strip_tags(substr($item->soal->konten_pertanyaan, 0, 100)) !!}...</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-komposisi" 
                                                data-url="{{ route('cbt.paket.remove-soal', [$paket->encrypted_id, encryptId($item->id)]) }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">Belum ada soal terpilih.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Bank Soal Tersedia --}}
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bank Soal Tersedia</h3>
                    </div>
                    <div class="card-body">
                        <form id="form-add-soal" action="{{ route('cbt.paket.add-soal', $paket->encrypted_id) }}" method="POST" class="ajax-form" data-redirect="true">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Pilih Soal (Pilihan Ganda)</label>
                                <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                                    @foreach($soalTersedia as $soal)
                                    <label class="list-group-item">
                                        <input class="form-check-input me-1" type="checkbox" name="soal_ids[]" value="{{ $soal->encrypted_id }}">
                                        <span class="d-block">
                                            <span class="badge bg-blue-lt mb-1">{{ $soal->mataUji->nama_mata_uji }}</span>
                                            <span class="d-block text-muted small">{!! strip_tags(substr($soal->konten_pertanyaan, 0, 150)) !!}</span>
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-2">
                                <i class="ti ti-plus"></i> Tambahkan ke Paket
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).on('click', '.btn-delete-komposisi', function() {
        var btn = $(this);
        var url = btn.data('url');
        
        Swal.fire({
            title: 'Hapus soal dari paket?',
            text: "Soal tidak terhapus dari Bank Soal, hanya dihapus dari paket ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        if (res.status === 'success') {
                            toastr.success(res.message);
                            location.reload();
                        } else {
                            toastr.error(res.message);
                        }
                    }
                });
            }
        });
    });
</script>
@endpush
