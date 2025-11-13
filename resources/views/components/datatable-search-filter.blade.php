<div class="row mb-3">
    <div class="col-md-3">
        <label class="form-label">Search</label>
        <input type="text" class="form-control global-search" id="globalSearch-{{ $dataTableId ?? 'dataTable' }}" placeholder="Search..." />
    </div>
    @if(isset($filters))
        @foreach($filters as $filter)
        <div class="col-md-{{$filter['col_size'] ?? 2}}">
            <label class="form-label">{{$filter['label']}}</label>
            @if($filter['type'] == 'select')
                <select class="form-control {{$filter['class'] ?? ''}} filter-select" id="{{$filter['id']}}" data-column="{{ $filter['column'] }}">
                    <option value="">{{$filter['placeholder'] ?? 'Select...'}}</option>
                    @foreach($filter['options'] as $value => $label)
                        <option value="{{$value}}" {{request($filter['name']) == $value ? 'selected' : ''}}>{{$label}}</option>
                    @endforeach
                </select>
            @else
                <input type="{{$filter['type'] ?? 'text'}}" class="form-control filter-input" id="{{$filter['id']}}" data-column="{{ $filter['column'] }}" placeholder="{{$filter['placeholder'] ?? ''}}" value="{{request($filter['name']) ?? ''}}" />
            @endif
        </div>
        @endforeach
    @endif
    <div class="col-md-2">
        <label class="form-label">&nbsp;</label>
        <button type="button" class="btn btn-primary w-100 filter-apply-btn" id="filterButton-{{ $dataTableId ?? 'dataTable' }}">Apply Filter</button>
    </div>
    <div class="col-md-1">
        <label class="form-label">&nbsp;</label>
        <button type="button" class="btn btn-secondary w-100 filter-clear-btn" id="clearFilterButton-{{ $dataTableId ?? 'dataTable' }}">Clear</button>
    </div>
</div>

<script>
    // Wait for the DataTable to be fully initialized before applying filters
    $(document).ready(function() {
        // Global search input for this table
        var globalSearchInput = $('#globalSearch-{{ $dataTableId ?? 'dataTable' }}');
        var filterButton = $('#filterButton-{{ $dataTableId ?? 'dataTable' }}');
        var clearFilterButton = $('#clearFilterButton-{{ $dataTableId ?? 'dataTable' }}');
        
        // Wait a bit for DataTable to initialize, then attach events
        setTimeout(function() {
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
                    @if(isset($filters))
                        @foreach($filters as $filter)
                            $('#{{ $filter['id'] }}').val('');
                        @endforeach
                    @endif
                    dataTable.search('').draw();
                    dataTable.ajax.reload();
                });
                
                // Handle individual filter changes
                @if(isset($filters))
                    @foreach($filters as $filter)
                        $('#{{ $filter['id'] }}').on('change', function() {
                            dataTable.ajax.reload();
                        });
                    @endforeach
                @endif
                
            } catch(e) {
                console.warn('DataTable not initialized yet for {{ $dataTableId ?? 'dataTable' }}:', e.message);
            }
        }, 500); // Wait 500ms to ensure DataTable is ready
    });
</script>