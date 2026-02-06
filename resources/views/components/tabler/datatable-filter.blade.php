@props([
    'dataTableId' => null,

])

<form id="{{ $dataTableId }}-filter">
    <div class="d-flex flex-wrap gap-2 mb-2">
        {{$slot}}
    </div>

    <!-- Active filters as badges -->
    <div id="{{ $dataTableId }}-active-filters" class="mb-2">
        <!-- Active filter badges will be displayed here -->
    </div>
</form>
