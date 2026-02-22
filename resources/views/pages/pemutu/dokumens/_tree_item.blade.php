<li data-id="{{ $dok->encrypted_dok_id }}" data-jenis="{{ $dok->jenis }}" id="tree-node-dok-{{ $dok->encrypted_dok_id }}">
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

        <a href="#" class="tree-item-link w-100" data-url="{{ route('pemutu.dokumen-spmi.show', ['type' => 'dokumen', 'id' => $dok->encrypted_dok_id]) }}" data-jenis="{{ $dok->jenis }}">
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
                @if(empty($child->parent_doksub_id))
                    @include('pages.pemutu.dokumens._tree_item', ['dok' => $child, 'level' => $level + 1, 'collapsed' => true])
                @endif
            @endforeach
        @endif

        @if($hasDokSubs)
            @foreach($dok->dokSubs as $sub)
                <li data-id="{{ $sub->encrypted_doksub_id }}" data-type="doksub" id="tree-node-sub-{{ $sub->encrypted_doksub_id }}">
                    <div class="d-flex align-items-start mb-2">
                        @php
                            $hasSubChildren = isset($sub->childDokumens) && $sub->childDokumens->count() > 0;
                        @endphp
                        @if($hasSubChildren)
                            <span class="tree-toggle text-muted me-2 mt-1">
                                <i class="ti ti-chevron-right"></i>
                            </span>
                        @else
                            <span class="text-muted me-2 mt-1" style="width: 20px; display: inline-block; text-align: center;">&bull;</span>
                        @endif
                        <a href="#" class="tree-item-link w-100" data-url="{{ route('pemutu.dokumen-spmi.show', ['type' => 'poin', 'id' => $sub->encrypted_doksub_id]) }}" data-jenis="doksub">
                            <div class="d-flex align-items-center mb-1">
                                <div class="row align-items-center w-100 gx-2">
                                    <div class="col-auto">
                                        <span class="avatar avatar-xs rounded ">
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
                    @if($hasSubChildren)
                        <ul class="list-unstyled ms-2 ps-2 nested-sortable d-none">
                            @foreach($sub->childDokumens as $childDocOfSub)
                                 @include('pages.pemutu.dokumens._tree_item', ['dok' => $childDocOfSub, 'level' => $level + 1, 'collapsed' => true])
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
</li>
