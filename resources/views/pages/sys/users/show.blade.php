@extends((request()->ajax() || request()->has('ajax')) ? 'layouts.tabler.empty' : 'layouts.tabler.app')

@section('header')
    <x-tabler.page-header title="{{ $user->name }}" pretitle="Detail Pengguna">
        <x-slot:actions>
            <x-tabler.button type="back" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <x-tabler.flash-message />

            <div class="row g-4">
                <!-- Profile Card -->
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <a href="{{$user->avatar_url}}" target="_blank">
                                    <img src="{{ $user->avatar_medium_url }}" 
                                         alt="user-avatar" 
                                         class="rounded-circle border border-3 border-white shadow-lg" 
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                </a>
                            </div>
                            
                            <h3 class="mb-1">{{ $user->name }}</h3>
                            <p class="text-muted mb-3">{{ $user->email }}</p>
                            
                            <div class="mb-3">
                                @if($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="status status-primary me-1">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                @else
                                    <span class="status status-secondary">No roles assigned</span>
                                @endif
                            </div>

                            <div class="d-grid gap-2">
                                @if(auth()->id() == $user->id)
                                    <x-tabler.button type="button" class="btn-primary ajax-modal-btn" data-url="{{ route('sys.profile.edit') }}" icon="ti ti-edit" text="Edit Profile" />
                                @else
                                    <x-tabler.button type="edit" :href="route('sys.users.edit', $user->encrypted_id)" text="Edit User" />
                                @endif
                                
                                <x-tabler.button type="button" class="btn-warning ajax-modal-btn" data-url="{{ route('sys.users.change-password') }}" icon="ti ti-key" text="Change Password" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information Card -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti ti-info-circle me-2"></i>Account Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Email Verification -->
                                <div class="col-md-4">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar avatar-sm bg-blue-lt me-2">
                                                <i class="ti ti-certificate"></i>
                                            </div>
                                            <div class="text-muted small">Email Verification</div>
                                        </div>
                                        <div class="h4 mb-0">
                                            @if($user->email_verified_at)
                                                <span class="status status-success fs-5">
                                                    <span class="status-dot status-dot-animated"></span> Verified
                                                </span>
                                                <div class="text-muted small mt-1">{{ formatTanggalIndo($user->email_verified_at) }}</div>
                                            @else
                                                <span class="status status-warning fs-5">
                                                    <span class="status-dot status-dot-animated"></span> Not Verified
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Member Since -->
                                <div class="col-md-4">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar avatar-sm bg-green-lt me-2">
                                                <i class="ti ti-calendar-plus"></i>
                                            </div>
                                            <div class="text-muted small">Member Since</div>
                                        </div>
                                        <div class="h4 mb-0">{{ formatTanggalIndo($user->created_at) }}</div>
                                        <div class="text-muted small">{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</div>
                                    </div>
                                </div>

                                <!-- Last Updated -->
                                <div class="col-md-4">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar avatar-sm bg-purple-lt me-2">
                                                <i class="ti ti-refresh"></i>
                                            </div>
                                            <div class="text-muted small">Last Updated</div>
                                        </div>
                                        <div class="h4 mb-0">{{ formatTanggalIndo($user->updated_at) }}</div>
                                        <div class="text-muted small">{{ \Carbon\Carbon::parse($user->updated_at)->diffForHumans() }}</div>
                                    </div>
                                </div>

                                <!-- Last Login -->
                                <div class="col-md-4">
                                    <div class="border rounded p-3 h-100">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar avatar-sm bg-cyan-lt me-2">
                                                <i class="ti ti-login"></i>
                                            </div>
                                            <div class="text-muted small">Last Login</div>
                                        </div>
                                        @if($user->last_login_at)
                                            <div class="h4 mb-0">{{ formatTanggalWaktuIndo($user->last_login_at) }}</div>
                                            <div class="text-muted small">{{ \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() }}</div>
                                        @else
                                            <div class="h4 mb-0 text-muted">Never</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Account Expiration -->
                                <div class="col-4">
                                    <div class="border rounded p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar avatar-sm bg-orange-lt me-2">
                                                <i class="ti ti-hourglass-empty"></i>
                                            </div>
                                            <div class="text-muted small">Account Expiration</div>
                                        </div>
                                        <div class="h4 mb-0">
                                            @if($user->expired_at)
                                                @if($user->expired_at->isFuture())
                                                    <span class="status status-info fs-5">
                                                        <span class="status-dot"></span> Expires {{ formatTanggalIndo($user->expired_at) }}
                                                    </span>
                                                    <span class="text-muted ms-2">({{ \Carbon\Carbon::parse($user->expired_at)->diffForHumans() }})</span>
                                                @else
                                                    <span class="status status-danger fs-5">
                                                        <span class="status-dot"></span> Expired on {{ formatTanggalIndo($user->expired_at) }}
                                                    </span>
                                                    <span class="text-danger ms-2">({{ \Carbon\Carbon::parse($user->expired_at)->diffForHumans() }})</span>
                                                @endif
                                            @else
                                                <span class="status status-success fs-5">
                                                    <span class="status-dot"></span> No Expiration
                                                </span>
                                                <span class="text-muted ms-2">Account never expires</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- User Logs Tabs -->
                <div class="col-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                                <li class="nav-item">
                                    <a href="#tabs-activity" class="nav-link active" data-bs-toggle="tab" data-dt-id="dt-activity">
                                        <i class="ti ti-activity me-2"></i>Log Aktivitas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tabs-notification" class="nav-link" data-bs-toggle="tab" data-dt-id="dt-notification">
                                        <i class="ti ti-bell me-2"></i>Notifikasi
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tabs-error" class="nav-link" data-bs-toggle="tab" data-dt-id="dt-error">
                                        <i class="ti ti-bug me-2"></i>Log Error
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Activity Tab -->
                                <div class="tab-pane active show" id="tabs-activity">
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <div><x-tabler.datatable-page-length dataTableId="dt-activity" /></div>
                                        <div class="ms-auto"><x-tabler.datatable-search dataTableId="dt-activity" /></div>
                                    </div>
                                    <x-tabler.datatable
                                        id="dt-activity" 
                                        route="{{ route('activity-log.data', ['causer_id' => $user->id]) }}" 
                                        :search="true"
                                        :columns="[
                                            ['title' => 'No', 'data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                                            ['title' => 'Log Name', 'data' => 'log_name', 'name' => 'log_name'],
                                            ['title' => 'Description', 'data' => 'description', 'name' => 'description'],
                                            ['title' => 'Causer', 'data' => 'causer_name', 'name' => 'causer_name'],
                                            ['title' => 'Date', 'data' => 'created_at', 'name' => 'created_at'],
                                            ['title' => 'Action', 'data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                                        ]" 
                                    />
                                </div>
                                
                                <!-- Notification Tab -->
                                <div class="tab-pane" id="tabs-notification">
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <div><x-tabler.datatable-page-length dataTableId="dt-notification" /></div>
                                        <div class="ms-auto"><x-tabler.datatable-search dataTableId="dt-notification" /></div>
                                    </div>
                                    <x-tabler.datatable
                                        id="dt-notification" 
                                        route="{{ route('notifications.data', ['notifiable_id' => $user->id]) }}" 
                                        :search="true"
                                        :columns="[
                                            ['title' => 'No', 'data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                                            ['title' => 'Tipe / Modul', 'data' => 'type', 'name' => 'type'],
                                            ['title' => 'Pesan / Data', 'data' => 'data', 'name' => 'data'],
                                            ['title' => 'Status', 'data' => 'read_at', 'name' => 'read_at'],
                                            ['title' => 'Waktu', 'data' => 'created_at', 'name' => 'created_at']
                                        ]" 
                                    />
                                </div>

                                <!-- Error Logs Tab -->
                                <div class="tab-pane" id="tabs-error">
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <div><x-tabler.datatable-page-length dataTableId="dt-error" /></div>
                                        <div class="ms-auto"><x-tabler.datatable-search dataTableId="dt-error" /></div>
                                    </div>
                                    <x-tabler.datatable
                                        id="dt-error" 
                                        route="{{ route('sys.error-log.data', ['user_id' => $user->id]) }}" 
                                        :search="true"
                                        :columns="[
                                            ['title' => 'No', 'data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                                            ['title' => 'Exception Message', 'data' => 'message', 'name' => 'message'],
                                            ['title' => 'File', 'data' => 'file_short', 'name' => 'file'],
                                            ['title' => 'Code', 'data' => 'code', 'name' => 'code'],
                                            ['title' => 'Time', 'data' => 'created_at', 'name' => 'created_at'],
                                            ['title' => 'Action', 'data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                                        ]" 
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Auto-activate tab from URL hash
    let hash = window.location.hash;
    if (hash) {
        let tabEl = document.querySelector('a[href="' + hash + '"]');
        if (tabEl) {
            // Using bootstrap Tab instance
            let tab = new bootstrap.Tab(tabEl);
            tab.show();
        }
    }

    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        if ($.fn.dataTable) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }
    });
});
</script>
@endpush
