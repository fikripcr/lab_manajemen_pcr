@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Create Renop Indicator
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('pemutu.renop.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Indicator</label>
                        <input type="text" name="indikator" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Target</label>
                        <input type="text" name="target" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parent Indicator (Optional)</label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- No Parent --</option>
                            @foreach($parents as $id => $title)
                                <option value="{{ $id }}">{{ $title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sequence</label>
                        <input type="number" name="seq" class="form-control" value="1">
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
