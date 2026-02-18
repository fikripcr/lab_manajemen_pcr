@php
    $media = $row->getFirstMedia('file_pegawai');
@endphp

@if ($media)
    <x-tabler.button href="{{ $media->getUrl() }}" target="_blank" class="btn-sm btn-icon btn-ghost-primary me-1" title="Download" icon="ti ti-download" />
@endif

<x-tabler.button type="button" onclick="deleteFile('{{ $row->hashid }}')" class="btn-sm btn-icon btn-ghost-danger" title="Hapus" icon="ti ti-trash" />
