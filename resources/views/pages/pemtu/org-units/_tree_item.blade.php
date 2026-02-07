<li data-id="{{ $unit->orgunit_id }}">
    <div class="d-flex align-items-center mb-1">
        @if($unit->activeChildren->count() > 0)
            <span class="tree-toggle text-muted me-1">
                <i class="ti ti-chevron-down"></i>
            </span>
        @else
            <span class="text-muted me-1" style="width: 20px; display: inline-block;">&bull;</span>
        @endif
        
        <a href="#" class="tree-item-link" data-url="{{ route('pemtu.org-units.show', $unit->orgunit_id) }}">
            {{ $unit->name }}
            @if($unit->code) <span class="text-muted small">({{ $unit->code }})</span> @endif
        </a>
    </div>

    @if($unit->activeChildren->count())
        <ul class="list-unstyled ms-2 ps-2 nested-sortable">
            @foreach($unit->activeChildren as $child)
                @include('pages.pemtu.org-units._tree_item', ['unit' => $child, 'level' => $level + 1])
            @endforeach
        </ul>
    @endif
</li>
