@props([
    'title' => 'Modal Title',
    'route' => '#',
    'method' => 'POST', // Use method="none" for purely view-based modals
    'id' => null,
    'id_form' => null,
    'size' => '', // modal-lg, modal-sm, etc.
    'submitText' => 'Simpan',
    'submitIcon' => 'ti-device-floppy',
    'hideFooter' => false // Set to true to hide the default footer buttons completely
])

@if($id)
<div class="modal modal-blur fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true" data-bs-focus="true">
    <div class="modal-dialog modal-dialog-centered {{ $size }}" role="document">
        <div class="modal-content">
@endif

            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            @if(strtolower($method) !== 'none')
            <form action="{{ $route }}" method="POST" class="ajax-form" @if(!in_array(strtolower($method), ['get', 'none'])) novalidate @endif @if($id_form) id="{{ $id_form }}" @endif {{ $attributes }}>
                @csrf
                @if(in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
                    @method($method)
                @endif
            @endif

                <div class="modal-body {{ $attributes->get('modal-body-class') }}">
                    {{ $slot }}
                </div>
                
                @if(!$hideFooter)
                    <div class="modal-footer">
                        @if(isset($footer))
                            {{ $footer }}
                        @else
                            <x-tabler.button type="cancel" data-bs-dismiss="modal" />
                            @if(strtolower($method) !== 'none')
                                <x-tabler.button type="submit" :icon="$submitIcon ? 'ti ' . $submitIcon : null" :text="$submitText" class="ms-auto" />
                            @endif
                        @endif
                    </div>
                @endif

            @if(strtolower($method) !== 'none')
            </form>
            @endif

@if($id)
        </div>
    </div>
</div>
@endif
