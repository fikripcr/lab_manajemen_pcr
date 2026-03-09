@props([
    'class' => ''
])

<div {{ $attributes->merge(['class' => 'card-footer ' . $class]) }}>
    {{ $slot }}
</div>
