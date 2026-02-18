@extends('layouts.tabler.app')

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
                    <x-tabler.form-input name="app_name" label="Application Name" id="app_name" value="{{ old('app_name', $config['app_name']) }}" />
                    @error('app_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">This will appear in the header and page titles.</div>
                </div>

                <div class="col-md-6">
                    <x-tabler.form-input type="url" name="app_url" label="Application URL" id="app_url" value="{{ old('app_url', $config['app_url']) }}" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="app_debug">Debug Mode</label>
                    <x-tabler.form-checkbox 
                        name="app_debug" 
                        label="Enable Debug Mode" 
                        id="app_debug" 
                        value="1" 
                        :checked="old('app_debug', $config['app_debug'])" 
                        switch 
                        class="mb-2"
                    />
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
                    <x-tabler.form-input name="mail_mailer" label="Mail Mailer" id="mail_mailer" value="{{ old('mail_mailer', $config['mail_mailer']) }}" />
                    @error('mail_mailer')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">SMTP, mail, sendmail, etc.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <x-tabler.form-input name="mail_host" label="Mail Host" id="mail_host" value="{{ old('mail_host', $config['mail_host']) }}" />
                    @error('mail_host')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-tabler.form-input type="number" name="mail_port" label="Mail Port" id="mail_port" value="{{ old('mail_port', $config['mail_port']) }}" />
                </div>

                <div class="col-md-6 mb-3">
                    <x-tabler.form-input name="mail_username" label="Mail Username" id="mail_username" value="{{ old('mail_username', $config['mail_username']) }}" />
                    @error('mail_username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-tabler.form-input type="password" name="mail_password" label="Mail Password" id="mail_password" value="{{ old('mail_password', $config['mail_password']) }}" />
                </div>

                <div class="col-md-6 mb-3">
                    <x-tabler.form-input name="mail_encryption" label="Mail Encryption" id="mail_encryption" value="{{ old('mail_encryption', $config['mail_encryption']) }}" />
                    @error('mail_encryption')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Example: tls, ssl</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-tabler.form-input type="email" name="mail_from_address" label="From Address" id="mail_from_address" value="{{ old('mail_from_address', $config['mail_from_address']) }}" />
                </div>

                <div class="col-md-6 mb-3">
                    <x-tabler.form-input name="mail_from_name" label="Mail From Name" id="mail_from_name" value="{{ old('mail_from_name', $config['mail_from_name']) }}" />
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
                    <x-tabler.form-input name="google_client_id" label="Google Client ID" id="google_client_id" value="{{ old('google_client_id', $config['google_client_id']) }}" />
                    @error('google_client_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <x-tabler.form-input type="password" name="google_client_secret" label="Google Client Secret" id="google_client_secret" value="{{ old('google_client_secret', $config['google_client_secret']) }}" />
                </div>
            </div>

                <div class="col-md-12">
                    <x-tabler.form-input type="url" name="google_redirect_uri" label="Google Redirect URI" id="google_redirect_uri" value="{{ old('google_redirect_uri', $config['google_redirect_uri']) }}" />
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
                    <x-tabler.form-input name="mysqldump_path" label="Mysqldump Path" id="mysqldump_path" value="{{ old('mysqldump_path', $config['mysqldump_path']) }}" />
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
                    <x-tabler.form-checkbox 
                        name="theme_customization_enabled" 
                        label="Enable Theme Customization" 
                        id="theme_customization_enabled" 
                        value="1" 
                        :checked="old('theme_customization_enabled', $config['theme_customization_enabled'])" 
                        switch 
                        class="mb-2"
                    />
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
                    <x-tabler.button type="submit" text="Bersihkan Cache" class="btn-warning me-2" icon="ti ti-trash" />
                </form>
            </div>

            <div class="col-md-6">
                <h6>Optimization</h6>
                <p class="text-muted">Optimize application performance by caching configuration, routes, and views.</p>
                <form action="{{ route('app-config.optimize') }}" method="POST" class="d-inline">
                    @csrf
                    <x-tabler.button type="submit" text="Optimalkan Aplikasi" class="btn-success" icon="ti ti-rocket" />
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
