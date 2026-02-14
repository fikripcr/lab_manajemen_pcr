@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.admin.empty' : 'layouts.admin.app')

@section('header')
    <x-tabler.page-header :title="$pengumuman->judul" pretitle="Announcement Details">
        <x-slot:actions>
            <x-tabler.button type="edit" :href="route('lab.'.$pengumuman->jenis.'.edit', $pengumuman)" />
            <x-tabler.button type="back" :href="route('lab.'.$pengumuman->jenis.'.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-tabler.flash-message />

                    <div class="datagrid">
                        <div class="datagrid-item">
                            <div class="datagrid-title">Title</div>
                            <div class="datagrid-content">{{ $pengumuman->judul }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Type</div>
                            <div class="datagrid-content">
                                <span class="badge bg-{{ $pengumuman->jenis == 'pengumuman' ? 'primary' : 'info' }}">
                                    {{ ucfirst($pengumuman->jenis) }}
                                </span>
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Author</div>
                            <div class="datagrid-content">{{ $pengumuman->penulis ? $pengumuman->penulis->name : 'System' }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Status</div>
                            <div class="datagrid-content">
                                <span class="badge bg-{{ $pengumuman->is_published ? 'success' : 'warning' }}">
                                    {{ $pengumuman->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Created At</div>
                            <div class="datagrid-content">{{ $pengumuman->created_at->format('d M Y H:i') }}</div>
                        </div>
                        <div class="datagrid-item">
                            <div class="datagrid-title">Last Updated</div>
                            <div class="datagrid-content">{{ $pengumuman->updated_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>

                    @php
                        $coverMedia = $pengumuman->getFirstMedia('info_cover');
                        $attachments = $pengumuman->getMedia('info_attachment');
                    @endphp

                    @if($coverMedia)
                        <div class="mt-4">
                            <h4 class="card-title mb-3">Cover Image</h4>
                            <img src="{{ $coverMedia->getFullUrl() }}" alt="Cover Image" class="rounded border shadow-sm" style="max-height: 300px;">
                        </div>
                    @endif

                    <div class="mt-4">
                        <h4 class="card-title mb-3">Content</h4>
                        <div class="p-3 border rounded bg-light">
                            {!! $pengumuman->isi !!}
                        </div>
                    </div>

                    @if($attachments->count() > 0)
                        <div class="mt-4">
                            <h4 class="card-title mb-3">Attachments</h4>
                            <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                                @foreach($attachments as $attachment)
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                        <div>
                                            <div class="fw-bold">{{ $attachment->file_name }}</div>
                                            <div class="text-muted small">{{ number_format($attachment->size / 1024, 2) }} KB • {{ strtoupper($attachment->extension) }}</div>
                                        </div>
                                        <x-tabler.button type="link" :href="$attachment->getFullUrl()" text="Download" icon="ti ti-download" class="btn-sm btn-outline-primary" target="_blank" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                        <x-tabler.button type="delete" 
                                    class="ajax-delete"
                                    :data-url="route('lab.'.$pengumuman->jenis.'.destroy', $pengumuman)"
                                    data-title="Hapus {{ ucfirst($pengumuman->jenis) }}"
                                    data-text="Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan."
                                    data-redirect="{{ route('lab.'.$pengumuman->jenis.'.index') }}"
                                    icon="ti ti-trash" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
