<ul>
    @foreach($children as $child)
        <li>
            <a href="#" onclick="return false;">
                <div class="node-content">
                    <div class="fw-bold">{{ $child->name }}</div>
                    <div class="text-muted small">{{ $child->type }}</div>
                </div>
            </a>
            @if($child->children && $child->children->count())
                @include('pages.pemutu.org-units._chart_recursive', ['children' => $child->children])
            @endif
        </li>
    @endforeach
</ul>
