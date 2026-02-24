<x-tabler.page-header title="{{ $pegawai->nama }}" pretitle="Detail Pegawai">
    <x-slot:actions>
        <div class="row g-2 align-items-center mb-3">
            <div class="col-auto">
                <span class="avatar avatar-lg rounded" style="background-image: url({{ $pegawai->latestDataDiri && $pegawai->latestDataDiri->file_foto ? asset($pegawai->latestDataDiri->file_foto) : asset('static/avatars/000m.jpg') }})"></span>
            </div>
            <div class="col">
                <div class="text-muted">NIP: {{ $pegawai->nip }} &bull; {{ $pegawai->email }}</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <x-tabler.button type="edit" href="{{ route('hr.pegawai.edit', encryptId($pegawai->pegawai_id)) }}" text="Edit Profile" />
            </div>
        </div>
    </x-slot:actions>
</x-tabler.page-header>
