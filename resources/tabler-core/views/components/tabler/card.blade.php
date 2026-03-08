@props([
    'class' => ''
])

<div {{ $attributes->merge(['class' => 'card overflow-hidden ' . $class]) }}>
    {{ $slot }}
</div>
