@if(request()->ajax() || request()->has('ajax'))
    <x-tabler.form-modal title="Semester Details" method="none">
        <div class="row">
            <div class="col-md-6 mb-3">
                <h6 class="text-muted">Tahun Ajaran:</h6>
                <p class="mb-0">{{ $semester->tahun_ajaran }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <h6 class="text-muted">Semester:</h6>
                <p class="mb-0">{{ $semester->semester == 1 ? 'Ganjil' : 'Genap' }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <h6 class="text-muted">Start Date:</h6>
                <p class="mb-0">{{ $semester->start_date }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <h6 class="text-muted">End Date:</h6>
                <p class="mb-0">{{ $semester->end_date }}</p>
            </div>
        </div>

        <div class="mb-3">
            <h6 class="text-muted">Status:</h6>
            <p class="mb-0">
                @if($semester->is_active)
                    <span class="badge bg-success text-white">Aktif</span>
                @else
                    <span class="badge bg-secondary text-white">Tidak Aktif</span>
                @endif
            </p>
        </div>
        
        <x-slot:footer>
            <x-tabler.button type="cancel" data-bs-dismiss="modal" text="Tutup" />
            <x-tabler.button type="edit" :href="route('lab.semesters.edit', $semester->encrypted_semester_id)" class="ms-auto" />
        </x-slot:footer>
    </x-tabler.form-modal>
@else
    @extends('layouts.tabler.app')

    @section('header')
        <x-tabler.page-header title="Semester Details">
            <x-slot:actions>
                <x-tabler.button type="back" :href="route('lab.semesters.index')" />
            </x-slot:actions>
        </x-tabler.page-header>
    @endsection

    @section('content')
        <x-tabler.flash-message />

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Tahun Ajaran:</h6>
                                <p class="mb-0">{{ $semester->tahun_ajaran }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Semester:</h6>
                                <p class="mb-0">{{ $semester->semester == 1 ? 'Ganjil' : 'Genap' }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Start Date:</h6>
                                <p class="mb-0">{{ $semester->start_date }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">End Date:</h6>
                                <p class="mb-0">{{ $semester->end_date }}</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted">Status:</h6>
                            <p class="mb-0">
                                @if($semester->is_active)
                                    <span class="badge bg-success text-white">Aktif</span>
                                @else
                                    <span class="badge bg-secondary text-white">Tidak Aktif</span>
                                @endif
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <form method="POST" action="{{ route('lab.semesters.destroy', $semester->encrypted_semester_id) }}">
                                @csrf
                                @method('DELETE')
                                <x-tabler.button type="delete" text="Delete Semester" onclick="return confirm('Are you sure you want to delete this semester?')" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif
