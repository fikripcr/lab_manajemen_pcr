@props([
    'class' => ''
])

<div {{ $attributes->merge(['class' => 'card-body ' . $class]) }}>
    {{ $slot }}
</div>
