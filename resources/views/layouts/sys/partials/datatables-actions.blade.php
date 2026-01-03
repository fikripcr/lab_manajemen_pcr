{{-- 
    Wrapper Partial for DataTables Actions 
    Restored to support Admin Controllers (e.g. UserController) that were reverted to use this partial.
    Internally uses the new logic component.
--}}
<x-sys.datatables-actions
    :edit-url="$editUrl ?? null"
    :edit-title="$editTitle ?? 'Edit'"
    :view-url="$viewUrl ?? null"
    :login-as-url="$loginAsUrl ?? null"
    :login-as-name="$loginAsName ?? null"
    :delete-url="$deleteUrl ?? null"
    :delete-title="$deleteTitle ?? 'Delete Data?'"
    :delete-text="$deleteText ?? 'Are you sure? This action cannot be undone!'"
/>
