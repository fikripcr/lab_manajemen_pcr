<li class="list-group-item p-0 border-0">
    <a href="#" class="org-unit-item d-flex align-items-center py-2 px-3 text-decoration-none text-body" data-id="{{ $unit->orgunit_id }}">
        <span class="me-2">
            @if($unit->children->count() > 0)
                <i class="ti ti-folder text-primary"></i>
            @else
                <i class="ti ti-point text-muted"></i>
            @endif
        </span>
        <span>{{ $unit->name }}</span>
        <span class="badge bg-secondary-lt ms-auto">{{ $unit->type }}</span>
    </a>
    @if($unit->children->count() > 0)
        <ul class="list-group list-group-flush ms-4">
            @foreach($unit->children as $child)
                @include('pages.hr.pegawai._mass_tree_item', ['unit' => $child])
            @endforeach
        </ul>
    @endif
</li>
