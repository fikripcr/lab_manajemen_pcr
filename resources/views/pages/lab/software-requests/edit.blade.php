@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
    <x-tabler.page-header :title="'Edit Request: ' . $softwareRequest->nama_software" pretitle="Software Request">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.software-requests.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('lab.software-requests.update', $softwareRequest->id) }}" method="POST" class="card ajax-form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <x-tabler.flash-message />

                    {{-- The original form tag was here, but the instruction implies the outer form should be the main one.
                         Keeping the content structure as close to the original as possible while applying the changes. --}}

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Nama Software:</h6>
                                <p class="mb-0 fw-bold">{{ $softwareRequest->nama_software }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Dosen:</h6>
                                <p class="mb-0">{{ $softwareRequest->dosen ? $softwareRequest->dosen->name : 'Guest' }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Mata Kuliah Terkait:</h6>
                                @if($softwareRequest->mataKuliahs->count() > 0)
                                    <div class="row">
                                        @foreach($softwareRequest->mataKuliahs as $mataKuliah)
                                            <div class="col-md-6 mb-2">
                                                <span class="badge bg-label-primary me-1">{{ $mataKuliah->kode_mk }} - {{ $mataKuliah->nama_mk }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted mb-0">Tidak ada mata kuliah terkait</p>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Tanggal Pengajuan:</h6>
                                <p class="mb-0">{{ $softwareRequest->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted">Keterangan / Deskripsi:</h6>
                            <div class="p-3 border rounded bg-light">
                                {!! $softwareRequest->deskripsi !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-tabler.form-select 
                                    name="status" 
                                    label="Status *" 
                                    :options="['menunggu_approval' => 'Menunggu Approval', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak']" 
                                    :selected="$softwareRequest->status"
                                />
                            </div>
                        </div>

                        <x-tabler.form-textarea type="editor" name="catatan" id="catatan-editor" label="Catatan Admin" :value="old('catatan', $softwareRequest->catatan)" height="200" />
                </div>
                <div class="card-footer text-end">
                    <x-tabler.button type="submit" text="Update Status" />
                    <x-tabler.button type="cancel" :href="route('lab.software-requests.index')" />
                </div>
            </form>
        </div>
    </div>
@endsection
