<x-tabler.form-modal
    :title="'Set Tim Auditee: ' . $unit->name"
    :route="route('pemutu.tim-mutu.store-auditee', [$periode->encrypted_periodespmi_id, $unit->encrypted_org_unit_id])"
    method="POST"
>
    {{-- Auditee --}}

        @if($auditee && $auditee->pegawai)
            <option value="{{ encryptId($auditee->pegawai_id) }}" selected>
                {{ $auditee->pegawai->nama }} ({{ $auditee->pegawai->nip ?? '-' }})
            </option>
        @endif
    </x-tabler.form-select>

    {{-- Anggota --}}

        @foreach($anggota as $member)
            @if($member->pegawai)
                <option value="{{ encryptId($member->pegawai_id) }}" selected>
                    {{ $member->pegawai->nama }} ({{ $member->pegawai->nip ?? '-' }})
                </option>
            @endif
        @endforeach
    </x-tabler.form-select>

</x-tabler.form-modal>

<script>

</script>
