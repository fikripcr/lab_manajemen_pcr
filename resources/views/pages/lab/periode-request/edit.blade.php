@extends('layouts.admin.app')

@section('header')
    <x-tabler.page-header :title="'Edit Periode: ' . $period->nama_periode" pretitle="Software Request">
        <x-slot:actions>
            <x-tabler.button type="back" :href="route('lab.periode-request.index')" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('lab.periode-request.update', encryptId($period->periodsoftreq_id)) }}" method="POST" class="card ajax-form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <x-tabler.flash-message />

                    <div class="mb-3">
                        <x-tabler.form-select name="semester_id" label="Semester" :options="$semesters->mapWithKeys(fn($s) => [$s->semester_id => $s->tahun_ajaran . ' - ' . $s->semester])->toArray()" :selected="$period->semester_id" placeholder="-- Pilih Semester --" required />
                    </div>

                    <div class="mb-3">
                        <x-tabler.form-input name="nama_periode" label="Nama Periode" :value="old('nama_periode', $period->nama_periode)" placeholder="Misal: Periode Ganjil 2024/2025" required />
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-tabler.form-input type="date" name="start_date" label="Tanggal Mulai" :value="old('start_date', $period->start_date instanceof \DateTime ? $period->start_date->format('Y-m-d') : $period->start_date)" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-tabler.form-input type="date" name="end_date" label="Tanggal Selesai" :value="old('end_date', $period->end_date instanceof \DateTime ? $period->end_date->format('Y-m-d') : $period->end_date)" required />
                        </div>
                    </div>

                    <div class="mb-3">
                        <x-tabler.form-checkbox 
                            name="is_active" 
                            label="Set as Active Period" 
                            value="1" 
                            :checked="old('is_active', $period->is_active)" 
                            switch 
                        />
                    </div>
                </div>
                <div class="card-footer text-end">
                    <x-tabler.button type="submit" class="btn-primary" text="Update Periode" />
                    <x-tabler.button type="cancel" :href="route('lab.periode-request.index')" />
                </div>
            </form>
        </div>
    </div>
@endsection
