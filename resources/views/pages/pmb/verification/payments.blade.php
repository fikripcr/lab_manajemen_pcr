@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Verifikasi Pembayaran PMB</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-verifikasi-bayar" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'pendaftaran.no_pendaftaran', 'name' => 'pendaftaran.no_pendaftaran', 'title' => 'No. Pendaftaran'],
                        ['data' => 'pendaftaran.user.name', 'name' => 'pendaftaran.user.name', 'title' => 'Nama Calon'],
                        ['data' => 'jumlah_bayar', 'name' => 'jumlah_bayar', 'title' => 'Nominal'],
                        ['data' => 'bank_asal', 'name' => 'bank_asal', 'title' => 'Bank'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                    :url="route('pmb.verification.paginate-payments')"
                />
            </div>
        </div>
    </div>
</div>

@endsection
