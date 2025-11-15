<div class="row mb-3">

    <div class="col-md-3">
        {{-- <label class="form-label">Search</label> --}}
        <div class="input-group  input-group-merge">
            <span class="input-group-text"><i class="bx bx-search"></i></span>
            <input type="text" class="form-control global-search" id="globalSearch-{{ $dataTableId ?? 'dataTable' }}" placeholder="Search..." />
            <button type="button" class="btn btn-primary filter-clear-btn" id="clearFilterButton-{{ $dataTableId ?? 'dataTable' }}">Clear</button>
        </div>
    </div>

    @if (isset($filters))
        @foreach ($filters as $filter)
            <div class="col-md-{{ $filter['col_size'] ?? 2 }}">
                {{-- <div class="input-group  input-group-merge"> --}}
                    {{-- <span class="input-group-text"><i class="bx bx-filter"></i></span> --}}
                    {{-- <label class="form-label">{{$filter['label']}}</label> --}}
                    @if ($filter['type'] == 'select')
                        <select class="form-control {{ $filter['class'] ?? '' }} filter-select" id="{{ $filter['id'] }}" data-column="{{ $filter['column'] }}">
                            <option value="">{{ $filter['placeholder'] ?? 'Select...' }}</option>
                            @foreach ($filter['options'] as $value => $label)
                                <option value="{{ $value }}" {{ request($filter['name']) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="{{ $filter['type'] ?? 'text' }}" class="form-control filter-input" id="{{ $filter['id'] }}" data-column="{{ $filter['column'] }}" placeholder="{{ $filter['placeholder'] ?? '' }}" value="{{ request($filter['name']) ?? '' }}" />
                    @endif
                {{-- </div> --}}
            </div>
        @endforeach
    @endif
</div>

@push('scripts')
    <script>
        // Wait for the DataTable to be fully initialized before applying filters
        $(document).ready(function() {
            // Initialize Choice.js for select elements with 'choice-select' class
            const choiceElements = document.querySelectorAll('.choice-select');


            // Global search input for this table
            var globalSearchInput = $('#globalSearch-{{ $dataTableId ?? 'dataTable' }}');
            var filterButton = $('#filterButton-{{ $dataTableId ?? 'dataTable' }}');
            var clearFilterButton = $('#clearFilterButton-{{ $dataTableId ?? 'dataTable' }}');

            // Wait a bit for DataTable to initialize, then attach events
                choiceElements.forEach(function(element) {
                new Choices(element, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Search...',
                    noResultsText: 'No results found',
                    itemSelectText: false,
                    shouldSort: false,
                });
            });
                try {
                    var dataTable = $('#{{ $dataTableId ?? 'dataTable' }}').DataTable();

                    // Handle global search - use DataTable's built-in search
                    globalSearchInput.on('keyup', function() {
                        dataTable.search(this.value).draw();
                    });

                    // Handle filter apply button
                    filterButton.on('click', function() {
                        dataTable.ajax.reload();
                    });

                    // Handle clear filter button
                    clearFilterButton.on('click', function() {
                        globalSearchInput.val('');
                        @if (isset($filters))
                            @foreach ($filters as $filter)
                                $('#{{ $filter['id'] }}').val('');
                                // Update the choice instance if it exists
                                const choiceElement = document.getElementById('{{ $filter['id'] }}');
                                if (choiceElement && choiceElement.choicesInstance) {
                                    choiceElement.choicesInstance.clearStore();
                                }
                            @endforeach
                        @endif
                        dataTable.search('').draw();
                        dataTable.ajax.reload();
                    });

                    // Handle individual filter changes
                    @if (isset($filters))
                        @foreach ($filters as $filter)
                            $('#{{ $filter['id'] }}').on('change', function() {
                                dataTable.ajax.reload();
                            });
                        @endforeach
                    @endif

                } catch (e) {
                    console.warn('DataTable not initialized yet for {{ $dataTableId ?? 'dataTable' }}:', e.message);
                }
        });
    </script>
@endpush
