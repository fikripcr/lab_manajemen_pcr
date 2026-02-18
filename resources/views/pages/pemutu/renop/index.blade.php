@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Indikator Rencana Operasional (Renop)" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.renop.create') }}" class="btn-primary" icon="ti ti-plus" text="Tambah Renop" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <x-tabler.datatable-client 
        id="table-renop" 
        :columns="[
            ['name' => 'Indikator'],
            ['name' => 'Target'],
            ['name' => 'Aksi', 'orderable' => false, 'searchable' => false, 'className' => 'text-end']
        ]"
    >
        @foreach($renops as $renop)
        <tr>
            <td>{{ $renop->indikator }}</td>
            <td>{{ $renop->target }}</td>
            <td class="text-end">
                <x-tabler.button href="#" class="btn-ghost-primary" size="sm" icon="ti ti-user-plus" text="Assign" />
            </td>
        </tr>
        @endforeach
    </x-tabler.datatable-client>

    @if($renops->hasPages())
    <div class="card-footer d-flex align-items-center">
        {{ $renops->links() }}
    </div>
    @endif
</div>
@endsection
