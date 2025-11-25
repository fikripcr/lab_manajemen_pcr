@props([
    'dataTableId' => null,
])

<div class="position-relative">
    <input type="text" id="{{$dataTableId . '-search'}}"
        class="form-control"
        placeholder="Search..."
        style="padding-left: 2.5rem; padding-right: 2rem;"
    />
    <span class="position-absolute start-0 top-50 translate-middle-y ms-3 text-muted">
        <i class="bx bx-search"></i>
    </span>
    <button type="button"
        class="btn position-absolute end-0 top-50 translate-middle-y me-2 btn-sm d-none"
        id="{{ $dataTableId . '-clear-search' }}"
        title="Clear search"
        onclick="clearSearch('{{ $dataTableId }}')"
    >
        <i class="bx bx-x"></i>
    </button>
</div>
