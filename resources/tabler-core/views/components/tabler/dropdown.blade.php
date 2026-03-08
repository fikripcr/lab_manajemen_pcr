@props([
    'icon' => 'ti ti-dots-vertical',
    'trigger' => 'link', // link, button
    'text' => null,
    'buttonClass' => 'btn btn-primary',
    'class' => 'btn btn-icon btn-ghost-secondary rounded-circle no-caret',
    'placement' => 'end', // end, start
])

@php
    $finalClass = ($trigger === 'button') ? $buttonClass : $class;
    $finalClass .= ' dropdown-toggle';
@endphp

<div class="dropdown">
    <a href="#" {{ $attributes->merge(['class' => trim($finalClass)]) }} data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        @if($icon) <i class="{{ $icon }} @if($text) me-1 @endif"></i> @endif
        @if($text) {{ $text }} @endif
    </a>
    <div class="dropdown-menu dropdown-menu-{{ $placement }} shadow-lg border-0">
        {{ $slot }}
    </div>
</div>
