@extends('layouts.sys.app')

@section('title', 'App Configuration')

@section('header')
<x-tabler.page-header title="App Configuration" pretitle="Others" />
@endsection

@section('content')

<div class="card">
    <div class="card-body ">
        <form action="{{ route('app-config.update') }}" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="config_section" value="app">

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

                <div class="col-md-6 mb-3">
                    <!-- Empty column to maintain layout -->
                </div>
            </div>

            <div class="pt-4">
                <x-tabler.button type="submit" class="me-sm-3 me-1" />
                <x-tabler.button type="reset" class="btn-label-secondary" />
            </div>
        </form>

        <hr class="my-4">

        <!-- Mail Configuration Form -->
        <form action="{{ route('app-config.update') }}" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="config_section" value="mail">
            <h5>Mail Configuration</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="mail_mailer">Mailer</label>
                    <input type="text" class="form-control @error('mail_mailer') is-invalid @enderror" id="mail_mailer" name="mail_mailer" value="{{ old('mail_mailer', $config['mail_mailer']) }}">
                    @error('mail_mailer')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">SMTP, mail, sendmail, etc.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="mail_host">Mail Host</label>
                    <input type="text" class="form-control @error('mail_host') is-invalid @enderror" id="mail_host" name="mail_host" value="{{ old('mail_host', $config['mail_host']) }}">
                    @error('mail_host')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="mail_port">Mail Port</label>
                    <input type="number" class="form-control @error('mail_port') is-invalid @enderror" id="mail_port" name="mail_port" value="{{ old('mail_port', $config['mail_port']) }}">
                    @error('mail_port')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="mail_username">Mail Username</label>
                    <input type="text" class="form-control @error('mail_username') is-invalid @enderror" id="mail_username" name="mail_username" value="{{ old('mail_username', $config['mail_username']) }}">
                    @error('mail_username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="mail_password">Mail Password</label>
                    <input type="password" class="form-control @error('mail_password') is-invalid @enderror" id="mail_password" name="mail_password" value="{{ old('mail_password', $config['mail_password']) }}">
                    @error('mail_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="mail_encryption">Mail Encryption</label>
                    <input type="text" class="form-control @error('mail_encryption') is-invalid @enderror" id="mail_encryption" name="mail_encryption" value="{{ old('mail_encryption', $config['mail_encryption']) }}">
                    @error('mail_encryption')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Example: tls, ssl</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="mail_from_address">From Address</label>
                    <input type="email" class="form-control @error('mail_from_address') is-invalid @enderror" id="mail_from_address" name="mail_from_address" value="{{ old('mail_from_address', $config['mail_from_address']) }}">
                    @error('mail_from_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="mail_from_name">From Name</label>
                    <input type="text" class="form-control @error('mail_from_name') is-invalid @enderror" id="mail_from_name" name="mail_from_name" value="{{ old('mail_from_name', $config['mail_from_name']) }}">
                    @error('mail_from_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="pt-4">
                <x-tabler.button type="submit" class="me-sm-3 me-1" />
                <x-tabler.button type="reset" class="btn-label-secondary" />
            </div>
        </form>

        <hr class="my-4">

        <!-- Google OAuth Configuration Form -->
        <form action="{{ route('app-config.update') }}" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="config_section" value="google">
            <h5>Google OAuth Configuration</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="google_client_id">Google Client ID</label>
                    <input type="text" class="form-control @error('google_client_id') is-invalid @enderror" id="google_client_id" name="google_client_id" value="{{ old('google_client_id', $config['google_client_id']) }}">
                    @error('google_client_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="google_client_secret">Google Client Secret</label>
                    <input type="password" class="form-control @error('google_client_secret') is-invalid @enderror" id="google_client_secret" name="google_client_secret" value="{{ old('google_client_secret', $config['google_client_secret']) }}">
                    @error('google_client_secret')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label" for="google_redirect_uri">Google Redirect URI</label>
                    <input type="url" class="form-control @error('google_redirect_uri') is-invalid @enderror" id="google_redirect_uri" name="google_redirect_uri" value="{{ old('google_redirect_uri', $config['google_redirect_uri']) }}">
                    @error('google_redirect_uri')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="pt-4">
                <x-tabler.button type="submit" class="me-sm-3 me-1" />
                <x-tabler.button type="reset" class="btn-label-secondary" />
            </div>
        </form>

        <hr class="my-4">

        <!-- Database Backup Configuration Form -->
        <form action="{{ route('app-config.update') }}" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="config_section" value="backup">
            <h5>Database Backup Configuration</h5>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label" for="mysqldump_path">Mysqldump Path</label>
                    <input type="text" class="form-control @error('mysqldump_path') is-invalid @enderror" id="mysqldump_path" name="mysqldump_path" value="{{ old('mysqldump_path', $config['mysqldump_path']) }}">
                    @error('mysqldump_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Path to mysqldump executable (required for database backups). Example: C:/laragon/bin/mysql/mysql-8.0.30-winx64/bin/mysqldump.exe</div>
                </div>
            </div>

            <div class="pt-4">
                <x-tabler.button type="submit" class="me-sm-3 me-1" />
                <x-tabler.button type="reset" class="btn-label-secondary" />
            </div>
        </form>

        <hr class="my-4">

        <!-- Theme Customization Configuration Form -->
        <form action="{{ route('app-config.update') }}" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="config_section" value="customization">
            <h5>Theme Customization</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="theme_customization_enabled">Theme Settings Panel</label>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input @error('theme_customization_enabled') is-invalid @enderror" type="checkbox" id="theme_customization_enabled" name="theme_customization_enabled" value="1" {{ old('theme_customization_enabled', $config['theme_customization_enabled']) ? 'checked' : '' }}>
                        <label class="form-check-label" for="theme_customization_enabled">Enable Theme Customization</label>
                    </div>
                    @error('theme_customization_enabled')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text">When enabled, users will see the floating Theme Settings button that allows them to customize colors, fonts, layouts, and other appearance options in both sys and auth sections.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <!-- Empty column to maintain layout -->
                </div>
            </div>

            <div class="pt-4">
                <x-tabler.button type="submit" class="me-sm-3 me-1" />
                <x-tabler.button type="reset" class="btn-label-secondary" />
            </div>
        </form>

        <hr class="my-4">

        <div class="row">
            <div class="col-md-6">
                <h6>Cache Management</h6>
                <p class="text-muted">Clear application cache to refresh configuration and other cached data.</p>
                <form action="{{ route('app-config.clear-cache') }}" method="POST" class="d-inline">
                    @csrf
                    <x-tabler.button type="submit" text="Clear Cache" class="btn-warning me-2" icon="ti ti-trash" />
                </form>
            </div>

            <div class="col-md-6">
                <h6>Optimization</h6>
                <p class="text-muted">Optimize application performance by caching configuration, routes, and views.</p>
                <form action="{{ route('app-config.optimize') }}" method="POST" class="d-inline">
                    @csrf
                    <x-tabler.button type="submit" text="Optimize Application" class="btn-success" icon="ti ti-rocket" />
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
