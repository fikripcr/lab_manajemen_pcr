@props([
    'title' => 'No results found',
    'text' => 'Try adjusting your search or filter to find what you\'re looking for.',
    'icon' => 'ti ti-mood-empty',
    'actionRoute' => null,
    'actionText' => null,
    'actionClass' => 'btn-primary',
    'image' => null,
])

<div class="empty">
    @if($image)
        <div class="empty-img"><img src="{{ $image }}" height="128" alt=""></div>
    @else
        <div class="empty-icon">
            <i class="{{ $icon }}" style="font-size: 3rem; stroke-width: 1.5;"></i>
        </div>
    @endif
    
    <p class="empty-title">{{ $title }}</p>
    <p class="empty-subtitle text-muted">
        {{ $text }}
    </p>
    
    @if($actionRoute && $actionText)
        <div class="empty-action">
            <a href="{{ $actionRoute }}" class="btn {{ $actionClass }}">
                @if($icon && strpos($actionClass, 'btn-primary') !== false)
                    <i class="ti ti-plus me-2"></i>
                @endif
                {{ $actionText }}
            </a>
        </div>
    @endif
</div>
