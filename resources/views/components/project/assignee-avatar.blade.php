@if($user)
<span class="avatar avatar-xs" title="{{ $user->name }}">
    {{ substr($user->name, 0, 1) }}
</span>
@else
<span class="text-muted">
    <i class="ti ti-user"></i>
</span>
@endif
