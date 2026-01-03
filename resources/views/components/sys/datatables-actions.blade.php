@props([
    'editUrl' => null,
    'editTitle' => 'Edit',
    'viewUrl' => null,
    'loginAsUrl' => null,
    'loginAsName' => null,
    'deleteUrl' => null,
    'deleteTitle' => 'Delete Data?',
    'deleteText' => 'Are you sure? This action cannot be undone!',
    'customActions' => []
])

<div class="d-flex align-items-center">
    {{-- Edit Button (Direct Action) --}}
    @if($editUrl)
        <a href="#" 
           class="text-primary me-2 ajax-modal-btn" 
           data-url="{{ $editUrl }}" 
           data-modal-title="{{ $editTitle }}" 
           title="Edit">
            <i class="ti ti-edit"></i>
        </a>
    @endif

    {{-- Dropdown for extra actions --}}
    <div class="dropdown">
        <button type="button" class="btn btn-action text-secondary" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="ti ti-dots-vertical"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            @if($viewUrl)
                <a class="dropdown-item" href="{{ $viewUrl }}">
                    <i class="ti ti-eye me-1"></i> View
                </a>
            @endif

            @if($loginAsUrl)
                <a href="javascript:void(0)" class="dropdown-item" onclick="loginAsUser('{{ $loginAsUrl }}', '{{ $loginAsName }}')">
                    <i class="ti ti-login me-1"></i> Login As
                </a>
            @endif

            {{-- Custom Actions Slot/Array --}}
            @foreach($customActions as $action)
                <a class="dropdown-item {{ $action['class'] ?? '' }}" href="{{ $action['url'] }}" 
                   @if(isset($action['attributes'])) {!! $action['attributes'] !!} @endif>
                    @if(isset($action['icon'])) <i class="ti ti-{{ $action['icon'] }} me-1"></i> @endif
                    {{ $action['label'] }}
                </a>
            @endforeach

            {{ $slot ?? '' }}

            @if($deleteUrl)
                <a href="javascript:void(0)" class="dropdown-item text-danger ajax-delete" 
                   data-url="{{ $deleteUrl }}" 
                   data-title="{{ $deleteTitle }}" 
                   data-text="{{ $deleteText }}">
                    <i class="ti ti-trash me-1"></i> Delete
                </a>
            @endif
        </div>
    </div>
</div>
