<div class="row g-2 align-items-center mb-3">
    <div class="col-auto">
        <span class="avatar avatar-lg rounded" style="background-image: url({{ $pegawai->latestDataDiri->file_foto ? asset($pegawai->latestDataDiri->file_foto) : asset('static/avatars/000m.jpg') }})"></span>
    </div>
    <div class="col">
        <div class="page-pretitle">Detail Pegawai</div>
        <h2 class="page-title">
            {{ $pegawai->nama }}
        </h2>
        <div class="text-muted">NIP: {{ $pegawai->nip }} &bull; {{ $pegawai->email }}</div>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <a href="{{ route('hr.pegawai.edit', encryptId($pegawai->pegawai_id)) }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
            Edit Profile
        </a>
    </div>
</div>
