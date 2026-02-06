@props([
    'type' => 'submit', // create, back, submit, cancel, import, export
    'href' => null,
    'onclick' => null,
    'form' => null,
    'text' => null,
    'icon' => null,
    'class' => '',
    'modalTitle' => null,
    'modalUrl' => null,
])

@php
    $baseClass = 'btn';
    $defaultIcon = '';
    $defaultText = '';
    $colorClass = 'btn-primary';
    $extraAttributes = [];

    switch ($type) {
        case 'create':
            $colorClass = 'btn-primary';
            $defaultIcon = 'ti ti-plus';
            $defaultText = 'Create';
            break;
        case 'back':
            $colorClass = 'btn-secondary';
            $defaultIcon = 'ti ti-arrow-left';
            $defaultText = 'Back';
            break;
        case 'submit':
            $colorClass = 'btn-primary';
            $defaultIcon = 'ti ti-device-floppy'; // Save icon
            $defaultText = 'Save';
            break;
        case 'cancel':
            $colorClass = 'btn-outline-secondary';
            $defaultIcon = 'ti ti-x';
            $defaultText = 'Cancel';
            break;
        case 'import':
            $colorClass = 'btn-success';
            $defaultIcon = 'ti ti-file-import';
            $defaultText = 'Import';
            break;
        case 'export':
            $colorClass = 'btn-success';
            $defaultIcon = 'ti ti-file-export';
            $defaultText = 'Export';
            break;
        case 'delete':
            $colorClass = 'btn-danger';
            $defaultIcon = 'ti ti-trash';
            $defaultText = 'Delete';
            break;
        case 'reset':
            $colorClass = 'btn-secondary';
            $defaultIcon = 'ti ti-refresh';
            $defaultText = 'Reset';
            break;
        case 'warning':
            $colorClass = 'btn-warning';
            $defaultIcon = 'ti ti-alert-triangle';
            $defaultText = 'Warning';
            break;
        case 'success':
            $colorClass = 'btn-success';
            $defaultIcon = 'ti ti-check';
            $defaultText = 'Success';
            break;
        default:
            $colorClass = 'btn-primary';
            break;
    }

    $finalIcon = $icon ?? $defaultIcon;
    $finalText = $text ?? $defaultText;
    
    // Merge classes
    // Note: d-none d-sm-inline-block might hide it on mobile, be careful. 
    // Tabler usually handles responsiveness well. I will remove d-none for now to be safe on mobile unless requested.
    $classes = "$baseClass $colorClass $class";
    
    // Handle AJAX Modal special attributes (if this is a 'create' button intended for modals)
    if ($modalUrl) {
        $extraAttributes['data-url'] = $modalUrl;
        $extraAttributes['data-modal-title'] = $modalTitle ?? $finalText;
        $classes .= ' ajax-modal-btn';
    }

    // Determine tag
    $tag = (!$href) ? 'button' : 'a';
    
    // Determine Type attribute
    $typeAttr = 'button';
    if ($type === 'submit') $typeAttr = 'submit';
    if ($type === 'reset') $typeAttr = 'reset';
    
    // Smart Back/Cancel Logic
    // If type is back or cancel, and no specific onclick is provided, try to use history.back()
    // We check document.referrer to ensure we are staying within the app.
    if (($type === 'back' || $type === 'cancel') && !$onclick && $href) {
         $onclick = "if(document.referrer.indexOf(window.location.host) !== -1) { history.back(); return false; }";
    }

    // Props for tag
    $attributes = $attributes->merge(['class' => $classes]);
    @endphp

@if($tag === 'button')
    <button type="{{ $typeAttr }}" {{ $attributes }} @if($form) form="{{ $form }}" @endif @if($onclick) onclick="{{ $onclick }}" @endif @foreach($extraAttributes as $key => $val) {{ $key }}="{{ $val }}" @endforeach>
        @if($finalIcon) <i class="{{ $finalIcon }} icon"></i> @endif
        {{ $finalText }}
        {{ $slot }}
    </button>
@else
    <a href="{{ $href }}" {{ $attributes }} @foreach($extraAttributes as $key => $val) {{ $key }}="{{ $val }}" @endforeach>
        @if($finalIcon) <i class="{{ $finalIcon }} icon"></i> @endif
        {{ $finalText }}
        {{ $slot }}
    </a>
@endif

{{-- Mobile Icon Only Version (Optional, can be added later if needed) --}}
