@props([
    'title' => null,
    'subtitle' => null,
    'class' => ''
])

<div {{ $attributes->merge(['class' => 'card-header ' . $class]) }}>
    @if($title || $subtitle)
        <div>
            @if($title)
                <h3 class="card-title">{!! $title !!}</h3>
            @endif
            @if($subtitle)
                <p class="card-subtitle mt-1 mb-0">{!! $subtitle !!}</p>
            @endif
        </div>
    @endif
    
    {{ $slot }}

    @isset($actions)
        <div class="card-actions">
            {{ $actions }}
        </div>
    @endisset
</div>
