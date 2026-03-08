@props([
    'href' => '#',
    'icon' => null,
    'label' => null,
    'type' => 'link', // link, delete
    'url' => null,   // for ajax-modal or ajax-delete
    'title' => null, // for ajax-delete
    'text' => null,  // for ajax-delete
    'class' => '',
])

@php
    $classes = 'dropdown-item ' . $class;
    $defaultIcon = null;
    $defaultLabel = null;

    switch ($type) {
        case 'delete':
            $classes .= ' text-danger ajax-delete';
            $defaultIcon = 'ti ti-trash';
            $defaultLabel = 'Hapus';
            break;
        case 'edit':
            $classes .= ' ajax-modal-btn';
            $defaultIcon = 'ti ti-pencil';
            $defaultLabel = 'Edit';
            break;
    }

    $finalIcon = $icon ?? $defaultIcon;
    $finalLabel = $label ?? ($slot->isNotEmpty() ? null : $defaultLabel);
@endphp

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => trim($classes)]) }}
   @if($url) data-url="{{ $url }}" @endif
   @if($title) data-title="{{ $title }}" @endif
   @if($text) data-text="{{ $text }}" @endif
>
    @php
    $iconClasses = $finalIcon;
    if ($finalIcon && !preg_match('/text-\w+/', $finalIcon) && $type !== 'delete') {
        $iconClasses .= ' text-muted';
    }
    if ($finalLabel || $slot->isNotEmpty()) {
        $iconClasses .= ' me-2';
    }
@endphp
@if($finalIcon) <i class="{{ trim($iconClasses) }}"></i> @endif
    {{ $finalLabel ?? $slot }}
</a>
