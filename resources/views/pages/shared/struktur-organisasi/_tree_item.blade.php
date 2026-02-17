<li data-id="{{ $unit->orgunit_id }}" class="mb-1">
    <div class="d-flex align-items-center py-1">
        @if($unit->children && $unit->children->count() > 0)
            <span class="tree-toggle me-1">
                <i class="ti ti-chevron-down"></i>
            </span>
        @else
            <span class="me-1" style="width: 20px;"></span>
        @endif
        <a href="#" class="tree-item-link" data-id="{{ $unit->orgunit_id }}" data-url="{{ route('shared.struktur-organisasi.show', ['struktur_organisasi' => $unit->orgunit_id]) }}">
            {{ $unit->name }}
            <span class="badge bg-secondary-lt ms-1" style="font-size: 0.7em;">{{ $unit->type }}</span>
            @if($unit->code)
                <small class="text-muted">({{ $unit->code }})</small>
            @endif
        </a>
    </div>
    @if($unit->children && $unit->children->count() > 0)
        <ul class="nested-sortable">
            @foreach($unit->children as $child)
                @include('pages.shared.struktur-organisasi._tree_item', ['unit' => $child, 'level' => $level + 1])
            @endforeach
        </ul>
    @endif
</li>
