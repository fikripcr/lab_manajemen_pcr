@php
    $Kegiatan_id = request('Kegiatan_id');
@endphp
<x-tabler.form-modal 
    title="{{ $team->exists ? 'Edit Panitia' : 'Tambah Panitia' }}" 
    action="{{ $team->exists ? route('Kegiatan.teams.update', $team->hashid) : route('Kegiatan.teams.store') }}"
    method="{{ $team->exists ? 'PUT' : 'POST' }}"
>
    <input type="hidden" name="Kegiatan_id" value="{{ $Kegiatan_id ?: $team->Kegiatan_id }}">

    <div class="row g-3">
        <div class="col-12">
            <div class="form-label">Pilih Anggota</div>
            <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                <label class="form-selectgroup-item flex-fill">
                    <input type="radio" name="member_source" value="internal" class="form-selectgroup-input" {{ $team->memberable_id ? 'checked' : '' }}>
                    <span class="form-selectgroup-label d-flex align-items-center p-3">
                        <span class="me-3">
                            <span class="form-selectgroup-check"></span>
                        </span>
                        <span class="form-selectgroup-label-content">
                            <span class="form-selectgroup-title">User Internal</span>
                            <span class="form-selectgroup-subtitle">Pilih dari daftar user sistem</span>
                        </span>
                    </span>
                </label>
                <label class="form-selectgroup-item flex-fill">
                    <input type="radio" name="member_source" value="external" class="form-selectgroup-input" {{ !$team->memberable_id ? 'checked' : '' }}>
                    <span class="form-selectgroup-label d-flex align-items-center p-3">
                        <span class="me-3">
                            <span class="form-selectgroup-check"></span>
                        </span>
                        <span class="form-selectgroup-label-content">
                            <span class="form-selectgroup-title">Pihak Eksternal / Manual</span>
                            <span class="form-selectgroup-subtitle">Input nama secara manual</span>
                        </span>
                    </span>
                </label>
            </div>
        </div>

        <div id="internal-selector" class="col-12 {{ $team->memberable_id ? '' : 'd-none' }}">
            <x-tabler.form-select name="user_id" label="Pilih User">
                <option value="">-- Pilih User --</option>
                @foreach(App\Models\User::all() as $user)
                    <option value="{{ $user->id }}" {{ $team->memberable_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </x-tabler.form-select>
            <input type="hidden" name="memberable_type" value="App\Models\User">
        </div>

        <div id="external-input" class="col-12 {{ !$team->memberable_id ? '' : 'd-none' }}">
            <x-tabler.form-input name="name" label="Nama Lengkap" value="{{ $team->name }}" />
        </div>

        <div class="col-md-8">
            <x-tabler.form-input name="role" label="Jabatan/Peran" placeholder="Contoh: Koordinator Konsumsi" value="{{ $team->role }}" />
        </div>

        <div class="col-md-4">
            <x-tabler.form-select name="is_pic" label="Apakah PIC?">
                <option value="0" {{ !$team->is_pic ? 'selected' : '' }}>Bukan</option>
                <option value="1" {{ $team->is_pic ? 'selected' : '' }}>Ya (PIC Utama)</option>
            </x-tabler.form-select>
        </div>
    </div>
</x-tabler.form-modal>

<script>
    $('[name="member_source"]').on('change', function() {
        if ($(this).val() === 'internal') {
            $('#internal-selector').removeClass('d-none');
            $('#external-input').addClass('d-none');
        } else {
            $('#internal-selector').addClass('d-none');
            $('#external-input').removeClass('d-none');
        }
    });

    // Custom data processing before AJAX submit
    $('.ajax-form').on('beforeSubmit', function(e, formData) {
        let source = $('[name="member_source"]:checked').val();
        if (source === 'internal') {
            formData.memberable_id = $('[name="user_id"]').val();
            formData.memberable_type = 'App\\Models\\User';
            formData.name = null;
        } else {
            formData.memberable_id = null;
            formData.memberable_type = null;
        }
    });
</script>
