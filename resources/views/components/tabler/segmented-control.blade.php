@props([
    'items' => [], // Array of ['id' => '...', 'label' => '...', 'icon' => '...', 'href' => '...']
    'active' => null,
    'name' => 'segmented_control',
    'id' => null,
])

@php
    $id = $id ?? 'segmented-' . Str::random(8);
@endphp

<div id="{{ $id }}" {{ $attributes->merge(['class' => 'btn-group p-1 bg-light rounded-pill shadow-sm d-inline-flex']) }} style="border: 1px solid rgba(0,0,0,0.05);">
    @foreach($items as $item)
        @php
            $itemId = $item['id'] ?? Str::slug($item['label']);
            $isActive = $active == $itemId;
            $hasHref = isset($item['href']);
            
            $activeClass = 'btn-white shadow-sm fw-bold border-0 active text-primary';
            $inactiveClass = 'btn-ghost-secondary border-0 opacity-75';
            $baseClass = 'btn rounded-pill px-4 transition-all duration-200 d-flex align-items-center';
        @endphp

        @if($hasHref)
            <a href="{{ $item['href'] }}" 
               class="{{ $baseClass }} {{ $isActive ? $activeClass : $inactiveClass }}">
                @if(isset($item['icon']))
                    <i class="{{ $item['icon'] }} me-2"></i>
                @endif
                {{ $item['label'] }}
            </a>
        @else
            <label class="m-0 cursor-pointer">
                <input type="radio" 
                       name="{{ $name }}" 
                       value="{{ $itemId }}" 
                       class="segmented-input d-none"
                       @checked($isActive)
                       @if(isset($item['target'])) 
                            data-bs-toggle="tab" 
                            data-bs-target="{{ $item['target'] }}"
                       @endif>
                <span class="{{ $baseClass }} {{ $isActive ? $activeClass : $inactiveClass }} segmented-btn-label">
                    @if(isset($item['icon']))
                        <i class="{{ $item['icon'] }} me-2"></i>
                    @endif
                    {{ $item['label'] }}
                </span>
            </label>
        @endif
    @endforeach
</div>

<style>
    .segmented-input:checked + .segmented-btn-label {
        background: #fff !important;
        color: var(--tblr-primary) !important;
        font-weight: bold !important;
        opacity: 1 !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08) !important;
    }
    .cursor-pointer {
        cursor: pointer;
    }
    @media (max-width: 768px) {
        .btn.rounded-pill.px-4 {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            font-size: 0.8rem;
        }
    }
</style>
