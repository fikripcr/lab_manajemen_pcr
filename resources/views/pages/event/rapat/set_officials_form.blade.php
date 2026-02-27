<x-tabler.form-modal
    title="Set Pejabat Rapat"
    route="{{ route('Kegiatan.rapat.update-officials', $rapat->hashid) }}"
    method="POST"
>
    @method('POST')
    <div class="mb-3">
        <x-tabler.form-select name="ketua_user_id" label="Ketua Rapat" class="select2" required="true">
            <option value="">Pilih Ketua...</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $rapat->ketua_user_id == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <x-tabler.form-select name="notulen_user_id" label="Notulen" class="select2" required="true">
            <option value="">Pilih Notulen...</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $rapat->notulen_user_id == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </x-tabler.form-select>
    </div>
</x-tabler.form-modal>
