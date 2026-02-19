@props([
    'title' => 'No results found',
    'text' => '',
    'icon' => 'ti ti-mood-empty',
    'actionRoute' => null,
    'actionText' => null,
    'actionClass' => 'btn-primary',
    'image' => null,
])

<div {{ $attributes->merge(['class' => 'empty']) }}>
    @if($image)
        <div class="empty-img"><img src="{{ $image }}" height="128" alt=""></div>
    @else
        <div class="empty-icon">
            <i class="{{ $icon }}" style="font-size: 3rem; stroke-width: 1.5;"></i>
        </div>
    @endif
    
    <p class="empty-title">{{ $title }}</p>
    @if($text)
        <p class="empty-subtitle text-muted">
            {{ $text }}
        </p>    
    @endif
    
    @if(isset($action))
        <div class="empty-action">
            {{ $action }}
        </div>
    @elseif($actionRoute && $actionText)
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
