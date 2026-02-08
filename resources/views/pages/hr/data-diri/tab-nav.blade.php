<div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
        <li class="nav-item">
            <a href="{{ route('hr.pegawai.show', $pegawai->encrypted_pegawai_id) }}" 
               class="nav-link {{ Route::is('hr.pegawai.show') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                Pegawai
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('hr.pegawai.keluarga.index', $pegawai->encrypted_pegawai_id) }}" 
               class="nav-link {{ Route::is('hr.pegawai.keluarga.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                Keluarga
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('hr.pegawai.pendidikan.index', $pegawai->encrypted_pegawai_id) }}" 
               class="nav-link {{ Route::is('hr.pegawai.pendidikan.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" /><path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" /></svg>
                Pendidikan
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('hr.pegawai.status-pegawai.index', $pegawai->encrypted_pegawai_id) }}" 
               class="nav-link {{ Route::is('hr.pegawai.status-pegawai.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-id-badge-2 me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 12h3v4h-3z" /><path d="M10 6h-6a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h16a1 1 0 0 0 1 -1v-12a1 1 0 0 0 -1 -1h-6" /><path d="M10 3m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v3a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M14 16h2" /><path d="M14 12h2" /></svg>
                Status Pegawai
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('hr.pegawai.status-aktifitas.index', $pegawai->encrypted_pegawai_id) }}" 
               class="nav-link {{ Route::is('hr.pegawai.status-aktifitas.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-activity me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12h4l3 8l4 -16l3 8h4" /></svg>
                Status Aktifitas
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('hr.pegawai.jabatan-fungsional.index', $pegawai->encrypted_pegawai_id) }}" 
               class="nav-link {{ Route::is('hr.pegawai.jabatan-fungsional.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-briefcase me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /><path d="M12 12l0 .01" /><path d="M3 13a20 20 0 0 0 18 0" /></svg>
                Fungsional
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-certificate me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M13 17.5v4.5l2 -1.5l2 1.5v-4.5" /><path d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -1 1.73" /><path d="M6 9l12 0" /><path d="M6 12l3 0" /><path d="M6 15l2 0" /></svg>
                Inpassing
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('hr.pegawai.jabatan-struktural.index', $pegawai->encrypted_pegawai_id) }}" 
               class="nav-link {{ Route::is('hr.pegawai.jabatan-struktural.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-building-skyscraper me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M5 21v-14l8 -4l8 4v14" /><path d="M19 21v-8l-6 -6l-6 6v8" /></svg>
                Struktural
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('hr.pegawai.pengembangan.index', $pegawai->encrypted_pegawai_id) }}" 
               class="nav-link {{ Route::is('hr.pegawai.pengembangan.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trending-up me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                Pengembangan Diri
            </a>
        </li>
    </ul>
</div>
