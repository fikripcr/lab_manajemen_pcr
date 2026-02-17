<ul class="list-unstyled" id="org-tree">
    @foreach($orgUnits as $unit)
        @include('pages.shared.struktur-organisasi._tree_item', ['unit' => $unit, 'level' => 0])
    @endforeach
    @if($orgUnits->isEmpty())
        <li class="text-muted">Belum ada unit organisasi.</li>
    @endif
</ul>
