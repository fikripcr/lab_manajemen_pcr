@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Tambah Indikator Renop" pretitle="Renop">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.renop.index') }}" style="secondary" icon="ti ti-arrow-left" text="Kembali" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <form method="POST" action="{{ route('pemutu.renop.store') }}" class="card ajax-form">
            @csrf
            <div class="card-body">
                <div class="mb-3">
                    <x-tabler.form-input name="indikator" label="Indikator" required="true" />
                </div>
                <div class="mb-3">
                    <x-tabler.form-input name="target" label="Target" required="true" />
                </div>
                <div class="mb-3">
                    <x-tabler.form-select 
                        name="parent_id" 
                        label="Indikator Induk (Opsional)" 
                        :options="['' => '-- Tanpa Induk --'] + $parents->toArray()" 
                    />
                </div>
                <div class="mb-3">
                    <x-tabler.form-input name="seq" label="Urutan" type="number" value="1" />
                </div>
            </div>
            <div class="card-footer text-end">
                <x-tabler.button type="submit" style="primary" text="Simpan Renop" />
            </div>
        </form>
    </div>
</div>
@endsection
