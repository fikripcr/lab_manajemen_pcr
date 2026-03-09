@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Jawaban: {{ $survei->judul }}" pretitle="Survei Responses">
    <x-slot:actions>
        <x-tabler.button type="back" href="{{ route('survei.index') }}" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
        {{-- Summary Cards --}}
        <div class="row row-cards mb-4">
            <div class="col-sm-4">
                <x-tabler.card>
                    <x-tabler.card-body>
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Responden</div>
                        </div>
                        <div class="h1 mb-0 mt-2">{{ $survei->pengisian->count() }}</div>
                    </x-tabler.card-body>
                </x-tabler.card>
            </div>
            <div class="col-sm-4">
                <x-tabler.card class="mb-3">
                    <x-tabler.card-header title="Selesai" />
                    <x-tabler.card-body>
                        <div class="h1 mb-0 mt-2">{{ $survei->pengisian->where('status', 'selesai')->count() }}</div>
                    </x-tabler.card-body>
                </x-tabler.card>
            </div>
            <div class="col-sm-4">
                <x-tabler.card>
                    <x-tabler.card-body>
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Pertanyaan</div>
                        </div>
                        <div class="h1 mb-0 mt-2">{{ $survei->halaman->sum(fn($h) => $h->pertanyaan->count()) }}</div>
                    </x-tabler.card-body>
                </x-tabler.card>
            </div>
        </div>

        @if($survei->pengisian->isEmpty())
            <x-tabler.empty-state 
                title="Belum ada responden" 
                description="Survei ini belum memiliki jawaban dari responden." 
                icon="ti ti-mood-empty" />
        @else
            {{-- Responses Table --}}
            <x-tabler.card>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($survei->pengisian as $idx => $pengisian)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>
                                    @if($pengisian->user)
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-sm bg-primary-lt me-2">
                                                {{ strtoupper(substr($pengisian->user->name ?? 'A', 0, 1)) }}
                                            </span>
                                            {{ $pengisian->user->name ?? '-' }}
                                        </div>
                                    @else
                                        <span class="text-muted">Anonim</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">
                                        {{ $pengisian->waktu_selesai ? $pengisian->waktu_selesai->format('d M Y H:i') : ($pengisian->created_at ? $pengisian->created_at->format('d M Y H:i') : '-') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $pengisian->status == 'selesai' ? 'bg-success text-white' : 'bg-warning text-white' }}">
                                        {{ ucfirst($pengisian->status ?? 'progres') }}
                                    </span>
                                </td>
                                <td>
                                    <x-tabler.button size="sm" variant="outline-primary" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#jawaban-{{ $pengisian->id }}"
                                        aria-expanded="false"
                                        icon="ti ti-eye" label="Detail" />
                                </td>
                            </tr>
                            <tr class="collapse" id="jawaban-{{ $pengisian->id }}">
                                <td colspan="5" class="bg-azure-lt p-3">
                                    <div class="row g-2">
                                        @foreach($pengisian->jawaban as $jawaban)
                                        <div class="col-md-6">
                                            <x-tabler.card class="card-sm">
                                                <x-tabler.card-body class="p-2">
                                                    <div class="fw-bold text-muted small mb-1">
                                                        {{ $jawaban->pertanyaan->teks_pertanyaan ?? 'Pertanyaan tidak ditemukan' }}
                                                    </div>
                                                    <div>
                                                        @if($jawaban->opsi)
                                                            {{ $jawaban->opsi->teks_opsi }}
                                                        @elseif($jawaban->nilai_teks)
                                                            {{ $jawaban->nilai_teks }}
                                                        @elseif($jawaban->nilai_angka !== null)
                                                            {{ $jawaban->nilai_angka }}
                                                        @elseif($jawaban->nilai_tanggal)
                                                            {{ $jawaban->nilai_tanggal->format('d M Y') }}
                                                        @elseif($jawaban->nilai_json)
                                                            {{ implode(', ', $jawaban->nilai_json) }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </div>
                                                </x-tabler.card-body>
                                            </x-tabler.card>
                                        </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-tabler.card>
        @endif
@endsection
