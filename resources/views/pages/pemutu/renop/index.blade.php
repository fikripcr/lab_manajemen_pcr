@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Indikator Rencana Operasional (Renop)" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button type="create" href="{{ route('pemutu.renop.create') }}" class="ajax-modal-btn" text="Tambah Renop" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<x-tabler.card>
    <x-tabler.datatable-client
        id="table-renop"
        :columns="[
            ['name' => 'Indikator'],
            ['name' => 'Target'],
            ['name' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
        ]"
    >
        @foreach($renops as $renop)
        <tr>
            <td>
                {{ $renop->indikator }}
                @if($renop->children->count())
                    <ul class="mt-2 mb-0">
                        @foreach($renop->children as $child)
                            <li class="small">
                                {{ $child->indikator }} (Target: {{ $child->target }})
                                <a href="{{ route('pemutu.renop.edit', $child->encrypted_indikator_id) }}" class="ajax-modal-btn ms-1" title="Edit Child"><i class="ti ti-edit"></i></a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </td>
            <td>{{ $renop->target }}</td>
            <td class="text-center">
                <div class="btn-list justify-content-end">
                    <x-tabler.button href="{{ route('pemutu.renop.edit', $renop->encrypted_indikator_id) }}" class="btn-ghost-primary ajax-modal-btn" size="sm" icon="ti ti-edit" text="Edit" />
                    <x-tabler.button href="#" class="btn-ghost-danger ajax-delete" size="sm" icon="ti ti-trash" text="Hapus" data-url="{{ route('pemutu.renop.destroy', $renop->encrypted_indikator_id) }}" data-title="Hapus Renop?" />
                </div>
            </td>
        </tr>
        @endforeach
    </x-tabler.datatable-client>

    @if($renops->hasPages())
    <x-tabler.card-footer class="d-flex align-items-center">
        {{ $renops->links() }}
    </x-tabler.card-footer>
    @endif
</x-tabler.card>
@endsection
