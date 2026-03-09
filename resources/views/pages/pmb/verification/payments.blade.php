@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Verifikasi Pembayaran PMB" pretitle="PMB" />
@endsection

@section('content')

    <x-tabler.card>
        <x-tabler.card-body class="p-0">
            <x-tabler.datatable
                id="table-verifikasi-bayar"
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                    ['data' => 'pendaftaran.no_pendaftaran', 'name' => 'pendaftaran.no_pendaftaran', 'title' => 'No. Pendaftaran'],
                    ['data' => 'pendaftaran.user.name', 'name' => 'pendaftaran.user.name', 'title' => 'Nama Calon'],
                    ['data' => 'jumlah_bayar', 'name' => 'jumlah_bayar', 'title' => 'Nominal'],
                    ['data' => 'bank_asal', 'name' => 'bank_asal', 'title' => 'Bank'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                ]"
                :url="route('pmb.verification.payments.data')"
            />
        </x-tabler.card-body>
    </x-tabler.card>
@endsection
