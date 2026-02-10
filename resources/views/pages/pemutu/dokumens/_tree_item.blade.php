<li data-id="{{ $dok->dok_id }}" data-jenis="{{ $dok->jenis }}" id="tree-node-dok-{{ $dok->dok_id }}">
    <div class="d-flex align-items-start mb-2" draggable="true">
        @php
            $hasChildDocs = $dok->children->count() > 0;
            $hasDokSubs = isset($dok->dokSubs) && $dok->dokSubs->count() > 0;
            $hasChildren = $hasChildDocs || $hasDokSubs;
        @endphp
        @if($hasChildren)
            <span class="tree-toggle text-muted me-2 mt-1">
                <i class="ti ti-chevron-{{ isset($collapsed) && $collapsed ? 'right' : 'down' }}"></i>
            </span>
        @else
            <span class="text-muted me-2 mt-1" style="width: 20px; display: inline-block; text-align: center;">&bull;</span>
        @endif

        <a href="#" class="tree-item-link w-100" data-url="{{ route('pemutu.dokumens.show', $dok->dok_id) }}" data-jenis="{{ $dok->jenis }}">
            <div class="d-flex align-items-center mb-1">
                <div class="row align-items-center w-100 gx-2">
                    <div class="col-auto">
                        <span class="avatar avatar-xs rounded bg-muted-lt text-muted">
                            {{ substr($dok->judul, 0, 1) }}
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-truncate">
                            <span class="tree-item-name fw-bold text-reset">{{ $dok->judul }}</span>
                            @if($dok->kode)
                            <div class="text-muted small">{{ $dok->kode }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <ul class="list-unstyled ms-2 ps-2 nested-sortable {{ isset($collapsed) && $collapsed ? 'd-none' : '' }}">
        @if($hasChildDocs)
            @foreach($dok->children as $child)
                @include('pages.pemutu.dokumens._tree_item', ['dok' => $child, 'level' => $level + 1, 'collapsed' => true])
            @endforeach
        @endif

        @if($hasDokSubs)
            @foreach($dok->dokSubs as $sub)
                <li data-id="{{ $sub->doksub_id }}" data-type="doksub" id="tree-node-sub-{{ $sub->doksub_id }}">
                    <div class="d-flex align-items-start mb-2">
                        <span class="text-muted me-2 mt-1" style="width: 20px; display: inline-block; text-align: center;">&bull;</span>
                        <a href="#" class="tree-item-link w-100" data-url="{{ route('pemutu.dok-subs.show', $sub->doksub_id) }}" data-jenis="doksub">
                            <div class="d-flex align-items-center mb-1">
                                <div class="row align-items-center w-100 gx-2">
                                    <div class="col-auto">
                                        <span class="avatar avatar-xs rounded bg-blue-lt text-blue">
                                            {{ $sub->seq ?? substr($sub->judul, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="text-truncate">
                                            <span class="tree-item-name text-reset">{{ $sub->judul }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </li>
            @endforeach
        @endif
    </ul>
</li>
