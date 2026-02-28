<?php
namespace App\Http\Controllers\Lab;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\UserImportRequest;
use App\Http\Requests\Lab\UserRequest;
use App\Imports\UserImport;
use App\Models\User;
use App\Services\Sys\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.lab.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('pages.lab.users.create', compact('roles'));
    }

    /**
     * Process datatables ajax request.
     */
    public function paginate(Request $request)
    {
        // Reuse Service Query
        $users = $this->userService->getFilteredQuery($request->all());

        return DataTables::of($users)
            ->addIndexColumn()
            ->order(function ($query) {
                $query->latest('created_at');
            })
        // Filter Column logic usually handled by Service query, but if DataTables sends specific column search:
            ->filterColumn('roles', function ($query, $keyword) {
                $query->whereHas('roles', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('name', function ($user) {
                $userCreatedAt = formatTanggalIndo($user->created_at);

                $html  = '<div class="d-flex align-items-center">';
                $html .= '<div class="avatar flex-shrink-0 me-3">';
                $html .= '<img src="' . $user->avatar_small_url . '" alt="' . $user->name . '" class="rounded-circle w-px-40 h-40">';
                $html .= '</div>';
                $html .= '<div class="d-flex flex-column">';
                $html .= '<span class="text-nowrap">' . $user->name . '</span>';
                $html .= '<small class="text-muted">' . $userCreatedAt . '</small>';
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('expired_at', function ($user) {
                if ($user->expired_at) {
                    $isExpired     = $user->isExpired();
                    $formattedDate = formatTanggalIndo($user->expired_at);
                    $badgeClass    = $isExpired ? 'bg-label-danger' : 'bg-label-warning';
                    $statusText    = $isExpired ? 'Expired' : 'Active';

                    return '<span class="badge ' . $badgeClass . '">' . $formattedDate . ' (' . $statusText . ')</span>';
                }
                return '<span class="badge bg-label-success">No Expiration</span>';
            })
            ->editColumn('roles', function ($user) {
                $roles = $user->getRoleNames();
                if ($roles->isEmpty()) {
                    return '<span class="badge bg-label-secondary">No Role</span>';
                }

                $roleBadges = '';
                foreach ($roles as $role) {
                    $roleBadges .= '<span class="badge bg-label-primary me-1">' . ucfirst($role) . '</span>';
                }

                return $roleBadges;
            })
            ->addColumn('action', function ($user) {
                $encryptedId = encryptId($user->id);
                return view('components.tabler.datatables-actions', [
                    'editUrl'     => route('lab.users.edit', $encryptedId),
                    'editModal'   => true,
                    'viewUrl'     => route('lab.users.show', $encryptedId),
                    'loginAsUrl'  => route('lab.users.login.as', $encryptedId),
                    'loginAsName' => addslashes($user->name),
                    'deleteUrl'   => route('lab.users.destroy', $encryptedId),
                ])->render();
            })
            ->rawColumns(['name', 'roles', 'expired_at', 'action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        // Add avatar file to data if exists (handled by service)
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar');
        }

        $this->userService->createUser($validated);
        return jsonSuccess('Pengguna berhasil dibuat.', route('lab.users.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('pages.lab.users.show', compact('user'));
    }

    public function changePassword()
    {
        return view('pages.lab.users.ajax.change-password');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('pages.lab.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar');
        }

        $this->userService->updateUser($user->id, $validated);
        return jsonSuccess('Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user->id);
        return jsonSuccess('Data berhasil dihapus.', route('lab.users.index'));
    }

    /**
     * Export users to Excel
     */
    public function export(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
        ];
        $columns = $request->get('columns', ['id', 'name', 'email', 'role_name']);

        $export = new UserExport($filters, $columns);

        return Excel::download($export, 'users_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    /**
     * Export users to PDF (summary or detail based on parameters)
     */
    public function exportPdf(Request $request, User $user = null)
    {
        if ($user) {
            // Detail report for specific user
            $data = [
                'user'       => $user,
                'reportType' => 'detail',
                'reportDate' => now()->format('d M Y H:i'),
            ];

            $pdf = Pdf::loadView('pages.lab.users.pdf.detail', $data);
            return $pdf->download('user-detail-' . $user->name . '-' . now()->format('Y-m-d-H-i') . '.pdf');
        } else {
            // Summary report for all users
            $type    = $request->get('type', 'summary');
            $filters = $request->all(); // Pass all filters to Service

            // Use Service to get Query
            $query = $this->userService->getFilteredQuery($filters);
            $users = $query->get();

            $data = [
                'users'       => $users,
                'summaryType' => $type,
                'reportDate'  => now()->format('d M Y H:i'),
                'filters'     => $filters,
            ];

            $pdf = Pdf::loadView('pages.lab.users.pdf.export', $data);
            return $pdf->download('users-report-' . $type . '-' . now()->format('Y-m-d-H-i') . '.pdf');
        }
    }

    /**
     * Show the import form for users.
     */
    public function showImport()
    {
        $roles = Role::all();
        return view('pages.lab.users.import', compact('roles'));
    }

    /**
     * Import users from Excel file.
     */
    public function import(UserImportRequest $request)
    {
        $file = $request->file('file');

        $defaultRole       = $request->input('role_default');
        $overwriteExisting = $request->input('overwrite_existing', false);

        $import = new UserImport($defaultRole, $overwriteExisting);
        Excel::import($import, $file);

        logActivity('user', 'Import users from file.');

        return redirect()->route('lab.users.index')
            ->with('success', "Import completed successfully. Users have been added to the database.");
    }

    /**
     * Login as a specific user using laravel-impersonate.
     */
    public function loginAs(Request $request, User $user)
    {
        $allowedRoles = ['admin', 'kepala_lab', 'ketua_jurusan'];

        // Permission check logic
        // This is Access Control, fits in Controller or Middleware or Policy.
        // Service handles Business Logic.
        $hasPermission = false;
        foreach ($allowedRoles as $role) {
            if (auth()->user()->hasRole($role)) {
                $hasPermission = true;
                break;
            }
        }

        $targetUser = $user;

        if (! $targetUser) {
            abort(404);
        }

        // Use laravel-impersonate
        app('impersonate')->take(auth()->user(), $targetUser);

        logActivity('impersonation', 'User impersonated ' . $targetUser->name . ' (ID: ' . $targetUser->id . ')', $targetUser);

        return jsonSuccess('Impersonation successful', route('lab.dashboard'), [
            'user_id' => $targetUser->id,
        ]);
    }

    /**
     * Leave impersonation and switch back to original user.
     */
    public function switchBack()
    {
        if (! app('impersonate')->isImpersonating()) {
            return redirect()->route('lab.dashboard')->with('error', 'Not currently impersonating anyone.');
        }

        $impersonator = app('impersonate')->getImpersonator();

        app('impersonate')->leave();

        if ($impersonator) {
            logActivity('impersonation', 'User switched back from impersonation to original account', $impersonator);
        }

        return redirect()->route('lab.dashboard')->with('success', 'Successfully switched back to original account.');
    }

    /**
     * Switch the active role for the authenticated user.
     */
    public function switchRole($role)
    {
        $user = auth()->user();

        // Verify that the user has this role
        $userRoles = $user->getRoleNames();
        if (! $userRoles->contains($role)) {
            return redirect()->back()->with('error', 'You do not have permission to switch to this role.');
        }

        // Set the active role in session
        setActiveRole($role);

        logActivity('role_switch', 'User switched active role to ' . $role, $user);

        return redirect()->back()->with('success', 'Successfully switched to ' . $role . ' role.');
    }
}
