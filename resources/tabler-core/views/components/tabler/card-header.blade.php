@props([
    'title' => null,
    'class' => ''
])

<div {{ $attributes->merge(['class' => 'card-header ' . $class]) }}>
    @if($title)
        <h3 class="card-title">{!! $title !!}</h3>
    @endif
    
    {{ $slot }}

    @isset($actions)
        <div class="card-actions">
            {{ $actions }}
        </div>
    @endisset
</div>
