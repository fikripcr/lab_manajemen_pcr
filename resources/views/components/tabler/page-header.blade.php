@props([
    'title',
    'pretitle' => null,
])

<div class="row g-2 align-items-center">
    <div class="col">
        @if($pretitle)
            <div class="page-pretitle">{{ $pretitle }}</div>
        @endif
        <h2 class="page-title">{{ $title }}</h2>
    </div>
    @if(isset($actions))
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                {{ $actions }}
            </div>
        </div>
    @endif
</div>
