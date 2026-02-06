@props([
    'dataTableId' => null,
])

<div class="input-icon">
    {{-- Search Icon (Right side) --}}
    <span class="input-icon-addon">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <circle cx="10" cy="10" r="7" />
            <line x1="21" y1="21" x2="15" y2="15" />
        </svg>
    </span>

    <input type="text" 
           id="{{$dataTableId . '-search'}}"
           class="form-control"
           placeholder="Search..."
    />

    {{-- Clear Button (Positioned left of the icon) --}}
    <span class="input-icon-addon p-0" style="right: 3rem; pointer-events: auto;">
         <button type="button"
            class="btn-close d-none"
            id="{{ $dataTableId . '-clear-search' }}"
            title="Clear search"
            onclick="document.getElementById('{{ $dataTableId . '-search' }}').value = ''; document.getElementById('{{ $dataTableId . '-search' }}').dispatchEvent(new Event('input', { bubbles: true }));"
        ></button>
    </span>
</div>
