@extends('layouts.tabler.app')

@section('title', 'App Configuration')

@section('header')
<x-tabler.page-header title="System Hub" pretitle="System Management">
    <x-slot:actions>
        <x-tabler.button href="{{ route('sys.dashboard') }}" text="Kembali" icon="ti ti-arrow-left" class="btn-outline-secondary" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('activity-log.*') ? 'active fw-bold' : '' }}" href="{{ route('sys.activity-log.index') }}">
                    <i class="ti ti-activity me-1"></i> Activity Log
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('notifications.*') ? 'active fw-bold' : '' }}" href="{{ route('sys.notifications.index') }}">
                    <i class="ti ti-bell me-1"></i> Notifications
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('sys.error-log.*') ? 'active fw-bold' : '' }}" href="{{ route('sys.error-log.index') }}">
                    <i class="ti ti-bug me-1"></i> Error Log
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('app-config') ? 'active fw-bold' : '' }}" href="{{ route('app-config') }}">
                    <i class="ti ti-settings me-1"></i> App Configuration
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active text-start" id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab" aria-controls="v-pills-general" aria-selected="true">
                        <i class="ti ti-world me-2"></i> General Settings
                    </button>
                    <button class="nav-link text-start" id="v-pills-mail-tab" data-bs-toggle="pill" data-bs-target="#v-pills-mail" type="button" role="tab" aria-controls="v-pills-mail" aria-selected="false">
                        <i class="ti ti-mail me-2"></i> Mail Settings
                    </button>
                    <button class="nav-link text-start" id="v-pills-google-tab" data-bs-toggle="pill" data-bs-target="#v-pills-google" type="button" role="tab" aria-controls="v-pills-google" aria-selected="false">
                        <i class="ti ti-brand-google me-2"></i> Google OAuth
                    </button>
                    <button class="nav-link text-start" id="v-pills-backup-tab" data-bs-toggle="pill" data-bs-target="#v-pills-backup" type="button" role="tab" aria-controls="v-pills-backup" aria-selected="false">
                        <i class="ti ti-database me-2"></i> Backup Settings
                    </button>
                    <button class="nav-link text-start" id="v-pills-theme-tab" data-bs-toggle="pill" data-bs-target="#v-pills-theme" type="button" role="tab" aria-controls="v-pills-theme" aria-selected="false">
                        <i class="ti ti-palette me-2"></i> Theme & UI
                    </button>
                    <button class="nav-link text-start" id="v-pills-maintenance-tab" data-bs-toggle="pill" data-bs-target="#v-pills-maintenance" type="button" role="tab" aria-controls="v-pills-maintenance" aria-selected="false">
                        <i class="ti ti-tool me-2"></i> Maintenance
                    </button>
                </div>
            </div>
            <div class="col-md-9 pt-3 pt-md-0 border-start">
                <div class="tab-content" id="v-pills-tabContent">
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel" aria-labelledby="v-pills-general-tab">
                        <form action="{{ route('app-config.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="config_section" value="app">
                            <h4 class="mb-3">General Settings</h4>
                            <div class="row g-3">
                                <div class="col-12">
                                    <x-tabler.form-input name="app_name" label="Application Name" value="{{ old('app_name', $config['app_name']) }}" help="This will appear in the header and page titles." />
                                </div>
                                <div class="col-12">
                                    <x-tabler.form-input type="url" name="app_url" label="Application URL" value="{{ old('app_url', $config['app_url']) }}" />
                                </div>
                                <div class="col-12">
                                    <x-tabler.form-checkbox name="app_debug" label="Enable Debug Mode" value="1" :checked="old('app_debug', $config['app_debug'])" switch help="When enabled, detailed error messages will be shown." />
                                </div>
                            </div>
                            <div class="mt-4">
                                <x-tabler.button type="submit" text="Ganti Sistem" class="btn-primary" />
                            </div>
                        </form>
                    </div>

                    <!-- Mail Settings -->
                    <div class="tab-pane fade" id="v-pills-mail" role="tabpanel" aria-labelledby="v-pills-mail-tab">
                        <form action="{{ route('app-config.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="config_section" value="mail">
                            <h4 class="mb-3">Mail Configuration</h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-tabler.form-input name="mail_mailer" label="Mail Mailer" value="{{ old('mail_mailer', $config['mail_mailer']) }}" help="SMTP, mail, etc." />
                                </div>
                                <div class="col-md-6">
                                    <x-tabler.form-input name="mail_host" label="Mail Host" value="{{ old('mail_host', $config['mail_host']) }}" />
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-input type="number" name="mail_port" label="Mail Port" value="{{ old('mail_port', $config['mail_port']) }}" />
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-input name="mail_encryption" label="Mail Encryption" value="{{ old('mail_encryption', $config['mail_encryption']) }}" help="tls, ssl" />
                                </div>
                                <div class="col-md-4">
                                    <x-tabler.form-input name="mail_username" label="Mail Username" value="{{ old('mail_username', $config['mail_username']) }}" />
                                </div>
                                <div class="col-md-12">
                                    <x-tabler.form-input type="password" name="mail_password" label="Mail Password" value="{{ old('mail_password', $config['mail_password']) }}" />
                                </div>
                                <div class="col-md-6">
                                    <x-tabler.form-input type="email" name="mail_from_address" label="From Address" value="{{ old('mail_from_address', $config['mail_from_address']) }}" />
                                </div>
                                <div class="col-md-6">
                                    <x-tabler.form-input name="mail_from_name" label="Mail From Name" value="{{ old('mail_from_name', $config['mail_from_name']) }}" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <x-tabler.button type="submit" text="Save Changes" class="btn-primary" />
                            </div>
                        </form>
                    </div>

                    <!-- Google OAuth -->
                    <div class="tab-pane fade" id="v-pills-google" role="tabpanel" aria-labelledby="v-pills-google-tab">
                        <form action="{{ route('app-config.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="config_section" value="google">
                            <h4 class="mb-3">Google OAuth Configuration</h4>
                            <div class="row g-3">
                                <div class="col-12">
                                    <x-tabler.form-input name="google_client_id" label="Google Client ID" value="{{ old('google_client_id', $config['google_client_id']) }}" />
                                </div>
                                <div class="col-12">
                                    <x-tabler.form-input type="password" name="google_client_secret" label="Google Client Secret" value="{{ old('google_client_secret', $config['google_client_secret']) }}" />
                                </div>
                                <div class="col-12">
                                    <x-tabler.form-input type="url" name="google_redirect_uri" label="Google Redirect URI" value="{{ old('google_redirect_uri', $config['google_redirect_uri']) }}" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <x-tabler.button type="submit" text="Save Changes" class="btn-primary" />
                            </div>
                        </form>
                    </div>

                    <!-- Backup Settings -->
                    <div class="tab-pane fade" id="v-pills-backup" role="tabpanel" aria-labelledby="v-pills-backup-tab">
                        <form action="{{ route('app-config.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="config_section" value="backup">
                            <h4 class="mb-3">Backup Configuration</h4>
                            <div class="row g-3">
                                <div class="col-12">
                                    <x-tabler.form-input name="mysqldump_path" label="Mysqldump Path" value="{{ old('mysqldump_path', $config['mysqldump_path']) }}" help="Example: C:/laragon/bin/mysql/mysql-8.0.30/bin/mysqldump.exe" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <x-tabler.button type="submit" text="Save Changes" class="btn-primary" />
                            </div>
                        </form>
                    </div>

                    <!-- Theme Customization -->
                    <div class="tab-pane fade" id="v-pills-theme" role="tabpanel" aria-labelledby="v-pills-theme-tab">
                        <form action="{{ route('app-config.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="config_section" value="customization">
                            <h4 class="mb-3">Theme & UI Settings</h4>
                            <div class="row g-3">
                                <div class="col-12">
                                    <x-tabler.form-checkbox name="theme_customization_enabled" label="Enable Customization Panel" value="1" :checked="old('theme_customization_enabled', $config['theme_customization_enabled'])" switch help="Shows floating Theme Settings button for users." />
                                </div>
                            </div>
                            <div class="mt-4">
                                <x-tabler.button type="submit" text="Save Changes" class="btn-primary" />
                            </div>
                        </form>
                    </div>

                    <!-- Maintenance Settings -->
                    <div class="tab-pane fade" id="v-pills-maintenance" role="tabpanel" aria-labelledby="v-pills-maintenance-tab">
                        <h4 class="mb-3">Maintenance & Optimization</h4>
                        <div class="row g-4">
                            <div class="col-md-6 text-center border rounded p-4">
                                <div class="avatar bg-warning-lt text-warning mb-3" style="width: 60px; height: 60px;">
                                    <i class="ti ti-trash fs-1"></i>
                                </div>
                                <h5>Cache Management</h5>
                                <p class="text-muted small">Clear all cached application data.</p>
                                <form action="{{ route('app-config.clear-cache') }}" method="POST">
                                    @csrf
                                    <x-tabler.button type="submit" text="Bersihkan Cache" class="btn-warning w-100" />
                                </form>
                            </div>
                            <div class="col-md-6 text-center border rounded p-4">
                                <div class="avatar bg-success-lt text-success mb-3" style="width: 60px; height: 60px;">
                                    <i class="ti ti-rocket fs-1"></i>
                                </div>
                                <h5>Optimization</h5>
                                <p class="text-muted small">Cache routes, config, and views.</p>
                                <form action="{{ route('app-config.optimize') }}" method="POST">
                                    @csrf
                                    <x-tabler.button type="submit" text="Optimalkan Aplikasi" class="btn-success w-100" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
