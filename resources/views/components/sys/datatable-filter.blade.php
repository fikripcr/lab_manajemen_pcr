@props([
    'dataTableId' => null,

])

<form id="{{ $dataTableId }}-filter">
    <div class="d-flex flex-wrap gap-2">
        {{$slot}}
    </div>
</form>
