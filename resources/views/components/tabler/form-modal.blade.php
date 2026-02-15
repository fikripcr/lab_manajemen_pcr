@props([
    'title' => 'Modal Title',
    'route' => '#',
    'method' => 'POST',
    'id' => null,
    'size' => '' // modal-lg, modal-sm, etc.
])

<div class="modal-header">
    <h5 class="modal-title">{{ $title }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ $route }}" method="POST" class="ajax-form" {{ $attributes }}>
    @csrf
    @if(in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif
    
    <div class="modal-body">
        {{ $slot }}
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
            Batal
        </button>
        <button type="submit" class="btn btn-primary ms-auto">
            <i class="ti ti-device-floppy me-2"></i> Simpan
        </button>
    </div>
</form>
