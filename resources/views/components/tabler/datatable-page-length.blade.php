@props([
    'dataTableId' => null,
    'options' => ['10', '25', '50', 'All'],
])

<div class="dropdown">
    <button type="button" class="btn btn-primary-lt dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="{{ $dataTableId }}-pageLength-btn">
        <span id="{{ $dataTableId }}-pageLength-text">10</span>
    </button>
    
    <ul class="dropdown-menu" id="{{ $dataTableId }}-pageLength-menu">
        @foreach ($options as $option)
            <li>
                <a class="dropdown-item {{ $loop->first ? 'active' : '' }}" 
                   href="#" 
                   data-value="{{ $option }}"
                   onclick="event.preventDefault(); updatePageLength('{{ $dataTableId }}', '{{ $option }}', this);">
                    {{ $option }} row
                </a>
            </li>
        @endforeach
    </ul>
</div>

<!-- Hidden input to store the actual value for DataTable -->
<input type="hidden" id="{{ $dataTableId }}-pageLength" value="10">

@push('scripts')
<script>
function updatePageLength(dataTableId, value, element) {
    // Update hidden input
    document.getElementById(`${dataTableId}-pageLength`).value = value;
    
    // Update button text
    document.getElementById(`${dataTableId}-pageLength-text`).textContent = value;
    
    // Update active state
    const menu = document.getElementById(`${dataTableId}-pageLength-menu`);
    menu.querySelectorAll('.dropdown-item').forEach(item => {
        item.classList.remove('active');
    });
    element.classList.add('active');
    
    // Trigger DataTable page length change
    const table = $(`#${dataTableId}`).DataTable();
    if (table) {
        const pageLength = value === 'All' ? -1 : parseInt(value);
        table.page.len(pageLength).draw();
    }
}
</script>
@endpush
