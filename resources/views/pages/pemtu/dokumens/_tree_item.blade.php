<li data-id="{{ $dok->dok_id }}">
    <div class="d-flex align-items-start mb-2">
        @if($dok->children->count() > 0)
            <span class="tree-toggle text-muted me-2 mt-1">
                <i class="ti ti-chevron-down"></i>
            </span>
        @else
            <span class="text-muted me-2 mt-1" style="width: 20px; display: inline-block; text-align: center;">&bull;</span>
        @endif
        
        <a href="#" class="tree-item-link w-100" data-url="{{ route('pemtu.dokumens.show', $dok->dok_id) }}">
            <div class="d-flex align-items-center mb-1">
                @if($dok->jenis)
                    <span class="badge badge-outline text-blue me-2" style="font-size: 0.7rem; padding: 2px 6px;">
                        {{ strtoupper($dok->jenis) }} 
                        @if($dok->kode) <span class="ms-1 text-muted">({{ $dok->kode }})</span> @endif
                    </span>
                @endif
            </div>
            <div class="tree-item-title text-reset" style="line-height: 1.3;">
                {{ $dok->judul }}
            </div>
        </a>
    </div>

    <ul class="list-unstyled ms-2 ps-2 nested-sortable">
        @if($dok->children->count())
            @foreach($dok->children as $child)
                @include('pages.pemtu.dokumens._tree_item', ['dok' => $child, 'level' => $level + 1])
            @endforeach
        @endif
    </ul>
</li>
