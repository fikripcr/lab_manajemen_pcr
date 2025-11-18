@extends('layouts.admin.app')

@section('title', 'App Configuration')

@section('content')

    <!-- System Configuration Tab Section -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Application Configuration</h4>

        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-body tab-content">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('app-config.update') }}" method="POST">
                            @csrf
                            @method('POST')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="app_name">Application Name</label>
                                    <input type="text" class="form-control @error('app_name') is-invalid @enderror" id="app_name" name="app_name" value="{{ old('app_name', $config['app_name']) }}">
                                    @error('app_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">This will appear in the header and page titles.</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="app_url">Application URL</label>
                                    <input type="url" class="form-control @error('app_url') is-invalid @enderror" id="app_url" name="app_url" value="{{ old('app_url', $config['app_url']) }}">
                                    @error('app_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="app_env">Environment</label>
                                    <select class="form-select @error('app_env') is-invalid @enderror" id="app_env" name="app_env">
                                        <option value="local" {{ config('app.env') == 'local' ? 'selected' : '' }}>Local</option>
                                        <option value="production" {{ config('app.env') == 'production' ? 'selected' : '' }}>Production</option>
                                    </select>
                                    @error('app_env')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="app_debug">Debug Mode</label>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input @error('app_debug') is-invalid @enderror" type="checkbox" id="app_debug" name="app_debug" value="1" {{ old('app_debug', $config['app_debug']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="app_debug">Enable Debug Mode</label>
                                    </div>
                                    @error('app_debug')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">When enabled, detailed error messages will be shown.</div>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Update Configuration</button>
                                <button type="reset" class="btn btn-label-secondary">Reset</button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6">
                                <h6>Cache Management</h6>
                                <p class="text-muted">Clear application cache to refresh configuration and other cached data.</p>
                                <form action="{{ route('app-config.clear-cache') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning me-2">Clear Cache</button>
                                </form>
                            </div>

                            <div class="col-md-6">
                                <h6>Optimization</h6>
                                <p class="text-muted">Optimize application performance by caching configuration, routes, and views.</p>
                                <form action="{{ route('app-config.optimize') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Optimize Application</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
