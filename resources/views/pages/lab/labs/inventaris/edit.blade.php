@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('title', 'Edit Inventaris Lab: ' . $labInventaris->kode_inventaris)

@section('header')
    <x-tabler.page-header :title="'Edit Inventaris Lab: ' . $labInventaris->kode_inventaris" pretitle="Laboratorium">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.labs.inventaris.index', $labInventaris->encrypted_lab_id)" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <form action="{{ route('lab.labs.inventaris.update', [$labInventaris->encrypted_lab_id, $labInventaris->encrypted_id]) }}" method="POST" class="ajax-form">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="inventaris_id">Nama Alat</label>
                            <div class="col-sm-10">
                                <x-form.select2
                                    id="inventaris_id"
                                    name="inventaris_id"
                                    :options="$inventarisList->mapWithKeys(fn($item) => [$item->inventaris_id => $item->nama_alat . ' (' . $item->jenis_alat . ')'])->toArray()"
                                    :selected="old('inventaris_id', $labInventaris->inventaris_id)"
                                    required="true"
                                />
                                @error('inventaris_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="no_series">No Series</label>
                            <div class="col-sm-10">
                                <input
                                    type="text"
                                    class="form-control @error('no_series') is-invalid @enderror"
                                    id="no_series"
                                    name="no_series"
                                    value="{{ old('no_series', $labInventaris->no_series) }}"
                                    placeholder="Nomor seri atau kode tambahan" required
                                >
                                @error('no_series')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="tanggal_penempatan">Tanggal Penempatan</label>
                            <div class="col-sm-10">
                                <input
                                    type="date"
                                    class="form-control @error('tanggal_penempatan') is-invalid @enderror"
                                    id="tanggal_penempatan"
                                    name="tanggal_penempatan"
                                    value="{{ old('tanggal_penempatan', $labInventaris->tanggal_penempatan?->format('Y-m-d')) }}" required
                                >
                                @error('tanggal_penempatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="tanggal_penghapusan">Tanggal Penghapusan</label>
                            <div class="col-sm-10">
                                <input
                                    type="date"
                                    class="form-control @error('tanggal_penghapusan') is-invalid @enderror"
                                    id="tanggal_penghapusan"
                                    name="tanggal_penghapusan"
                                    value="{{ old('tanggal_penghapusan', $labInventaris->tanggal_penghapusan?->format('Y-m-d')) }}"
                                >
                                @error('tanggal_penghapusan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="status">Status</label>
                            <div class="col-sm-10">
                                <x-form.select2
                                    id="status"
                                    name="status"
                                    :options="[
                                        'active' => 'Active',
                                        'moved' => 'Moved',
                                        'inactive' => 'Inactive'
                                    ]"
                                    :selected="old('status', $labInventaris->status)"
                                />
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="keterangan">Keterangan</label>
                            <div class="col-sm-10">
                                <textarea
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    id="keterangan"
                                    name="keterangan"
                                    rows="3"
                                    placeholder="Tambahkan keterangan tambahan"
                                >{{ old('keterangan', $labInventaris->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" text="Simpan Perubahan" />
                                <x-tabler.button type="cancel" :href="route('lab.labs.inventaris.index', $labInventaris->encrypted_lab_id)" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="inventaris_id">Nama Alat</label>
                            <div class="col-sm-10">
                                <select
                                    class="form-select @error('inventaris_id') is-invalid @enderror"
                                    id="inventaris_id"
                                    name="inventaris_id"
                                    required
                                >
                                    <option value="">-- Pilih Inventaris --</option>
                                    @foreach($inventarisList as $item)
                                        <option value="{{ $item->inventaris_id }}" {{ old('inventaris_id', $labInventaris->inventaris_id) == $item->inventaris_id ? 'selected' : '' }}>
                                            {{ $item->nama_alat }} ({{ $item->jenis_alat }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('inventaris_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="no_series">No Series</label>
                            <div class="col-sm-10">
                                <input
                                    type="text"
                                    class="form-control @error('no_series') is-invalid @enderror"
                                    id="no_series"
                                    name="no_series"
                                    value="{{ old('no_series', $labInventaris->no_series) }}"
                                    placeholder="Nomor seri atau kode tambahan" required
                                >
                                @error('no_series')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label required" for="tanggal_penempatan">Tanggal Penempatan</label>
                            <div class="col-sm-10">
                                <input
                                    type="date"
                                    class="form-control @error('tanggal_penempatan') is-invalid @enderror"
                                    id="tanggal_penempatan"
                                    name="tanggal_penempatan"
                                    value="{{ old('tanggal_penempatan', $labInventaris->tanggal_penempatan?->format('Y-m-d')) }}" required
                                >
                                @error('tanggal_penempatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="tanggal_penghapusan">Tanggal Penghapusan</label>
                            <div class="col-sm-10">
                                <input
                                    type="date"
                                    class="form-control @error('tanggal_penghapusan') is-invalid @enderror"
                                    id="tanggal_penghapusan"
                                    name="tanggal_penghapusan"
                                    value="{{ old('tanggal_penghapusan', $labInventaris->tanggal_penghapusan?->format('Y-m-d')) }}"
                                >
                                @error('tanggal_penghapusan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="status">Status</label>
                            <div class="col-sm-10">
                                <select
                                    class="form-select @error('status') is-invalid @enderror"
                                    id="status"
                                    name="status"
                                >
                                    <option value="active" {{ old('status', $labInventaris->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="moved" {{ old('status', $labInventaris->status) == 'moved' ? 'selected' : '' }}>Moved</option>
                                    <option value="inactive" {{ old('status', $labInventaris->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="keterangan">Keterangan</label>
                            <div class="col-sm-10">
                                <textarea
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    id="keterangan"
                                    name="keterangan"
                                    rows="3"
                                    placeholder="Tambahkan keterangan tambahan"
                                >{{ old('keterangan', $labInventaris->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-10 offset-sm-2">
                                <x-tabler.button type="submit" text="Simpan Perubahan" />
                                <x-tabler.button type="cancel" :href="route('lab.labs.inventaris.index', $labInventaris->encrypted_lab_id)" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
