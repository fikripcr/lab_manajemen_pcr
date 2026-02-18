@props([
    'title' => 'Modal Title',
    'route' => '#',
    'method' => 'POST',
    'id' => null,
    'id_form' => null,
    'size' => '', // modal-lg, modal-sm, etc.
    'submitText' => 'Simpan',
    'submitIcon' => 'ti-device-floppy'
])

@if($id)
<div class="modal modal-blur fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered {{ $size }}" role="document">
        <div class="modal-content">
@endif

            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ $route }}" method="POST" class="ajax-form" @if($id_form) id="{{ $id_form }}" @endif {{ $attributes }}>
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
                        @if($submitIcon) <i class="ti {{ $submitIcon }} me-2"></i> @endif {{ $submitText }}
                    </button>
                </div>
            </form>

@if($id)
        </div>
    </div>
</div>
@endif
