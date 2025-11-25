@props([
    'dataTableId' => null,
])

<div class="input-group">
    <span class="input-group-text"><i class="bx bx-search"></i></span>
    <input type="text" id="{{$dataTableId . '-search'}}"
        class="form-control"
        placeholder="Search..."
    />
    {{-- <button
        type="button"
        class="btn btn-sm btn-outline-secondary"
        id="{{ $dataTableId }}-refresh"
        title="Refresh data"
    >
        <i class="bx bx-refresh"></i>
    </button> --}}
</div>
