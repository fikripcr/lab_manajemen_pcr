<div class="card mb-3">
    <div class="card-header">
        <div>
            <div class="d-flex align-items-center">
                <h3 class="card-title mb-0 me-2">{{ $orgUnit->name }}</h3>
                @if($orgUnit->code)
                    <span class="badge bg-secondary-lt me-2">{{ $orgUnit->code }}</span>
                @endif
                @if($orgUnit->type)
                    <span class="badge bg-blue-lt">{{ $orgUnit->type }}</span>
                @endif
            </div>
            <div class="text-muted small mt-1">
                @if($orgUnit->parent)
                    Berada dibawah: <a href="#" class="tree-item-link" data-url="{{ route('pemutu.org-units.show', $orgUnit->parent_id) }}">{{ $orgUnit->parent->name }}</a>
                @else
                    
                @endif
            </div>
        </div>
        <div class="card-actions">
            <div class="btn-group" role="group">
                <a href="#" class="btn btn-outline-secondary ajax-modal-btn" data-url="{{ route('pemutu.org-units.edit', $orgUnit->orgunit_id) }}" data-modal-title="Edit Unit">
                    <i class="ti ti-pencil"></i>
                </a>
                <a href="#" class="btn btn-outline-primary ajax-modal-btn" data-url="{{ route('pemutu.org-units.create', ['parent_id' => $orgUnit->orgunit_id]) }}" data-modal-title="Add Sub-Unit">
                    <i class="ti ti-plus me-2"></i> Sub-Unit
                </a>
                <a href="#" class="btn btn-outline-danger ajax-delete" data-url="{{ route('pemutu.org-units.destroy', $orgUnit->orgunit_id) }}" data-title="Delete Unit?" data-text="This will delete the unit and detach all personnels.">
                    <i class="ti ti-trash"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h4 class="card-title mb-0">Personil</h4>
            {{-- Placeholder for Add Personil (to be implemented with Personil module) --}}
            <x-tabler.button class="btn-sm btn-outline-primary" disabled icon="ti ti-user-plus" text="Add Personil" />
        </div>
    </div>
    <div class="card-table">
        <x-tabler.datatable-client
            id="table-personil"
            :columns="[
                ['name' => 'Name'],
                ['name' => 'Email'],
                ['name' => 'Type'],
                ['name' => '', 'className' => 'w-1']
            ]"
        >
            @forelse($orgUnit->personils as $personil)
                <tr>
                    <td>
                        <div>{{ $personil->nama }}</div>
                        @if($personil->user)<div class="text-muted small">User Linked</div>@endif
                    </td>
                    <td>{{ $personil->email ?? '-' }}</td>
                    <td>{{ $personil->jenis ?? '-' }}</td>
                    <td>
                        {{-- Actions --}}
                    </td>
                </tr>
            @empty
                {{-- Handled by component --}}
            @endforelse
        </x-tabler.datatable-client>
        
        @if($orgUnit->personils->isEmpty())
            <div class="text-center text-muted p-3">No personil assigned.</div>
        @endif
    </div>
</div>
