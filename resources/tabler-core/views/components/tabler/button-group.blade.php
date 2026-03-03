@props([
    'class' => '',
    'group' => false // If true, uses btn-group (attached). If false, uses btn-list (spaced).
])

@php
    $baseClass = $group ? 'btn-group' : 'btn-list';
    if ($group && !$class) {
        $class = 'shadow-sm'; // Default shadow for groups
    }
@endphp

<div {{ $attributes->merge(['class' => $baseClass . ' ' . $class, 'role' => 'group']) }}>
    {{ $slot }}
</div>
