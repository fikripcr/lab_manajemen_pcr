<?php
namespace App\Http\Controllers\Admin;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Imports\UserImport;
use App\Models\User;
use App\Services\Sys\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('pages.admin.users.create', compact('roles'));
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
                    'editUrl'     => route('users.edit', $encryptedId),
                    'viewUrl'     => route('users.show', $encryptedId),
                    'loginAsUrl'  => route('users.login.as', $encryptedId),
                    'loginAsName' => addslashes($user->name),
                    'deleteUrl'   => route('users.destroy', $encryptedId),
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

        try {
            $this->userService->createUser($validated);
            return jsonSuccess('Pengguna berhasil dibuat.', route('users.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $realId = decryptId($id);

        $user = $this->userService->getUserById($realId); // Uses Service
        if (! $user) {
            abort(404);
        }

        return view('pages.admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId = decryptId($id);

        $user = $this->userService->getUserById($realId);
        if (! $user) {
            abort(404);
        }

        $roles = Role::all();
        return view('pages.admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $id)
    {
        $realId    = decryptId($id);
        $validated = $request->validated();

        // Add avatar file to data if exists
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar');
        }
        // Transform 'role' to 'roles' for service consistency if needed,
        // service handles both keys but let's be standardized.
        // Controller validated 'role' (singular or array). Service checks both.

        try {
            $this->userService->updateUser($realId, $validated);

            return jsonSuccess('Pengguna berhasil diperbarui.', route('users.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $realId = decryptId($id);
            $this->userService->deleteUser($realId);

            return jsonSuccess('Data berhasil dihapus.', route('users.index'));
        } catch (\Exception $e) {
            return jsonError($e->getMessage(), 500);
        }
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
    public function exportPdf(Request $request, $id = null)
    {
        if ($id) {
            // Detail report for specific user
            $realId = decryptId($id);
            $user   = $this->userService->getUserById($realId);

            $data = [
                'user'       => $user,
                'reportType' => 'detail',
                'reportDate' => now()->format('d M Y H:i'),
            ];

            $pdf = Pdf::loadView('pages.admin.users.pdf.detail', $data);
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

            $pdf = Pdf::loadView('pages.admin.users.pdf.export', $data);
            return $pdf->download('users-report-' . $type . '-' . now()->format('Y-m-d-H-i') . '.pdf');
        }
    }

    /**
     * Show the import form for users.
     */
    public function showImport()
    {
        $roles = Role::all();
        return view('pages.admin.users.import', compact('roles'));
    }

    /**
     * Import users from Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('file');

            // Import using the UserImport class with parameters
            // Service could wrap this, but UserImport class is self-contained.
            // Let's keep it here for now unless I add 'importUsers' to service.
            // Added 'importPersonils' to PersonilService, so consistencies...
            // But UserImport takes parameters ($defaultRole) in Constructor.

            $defaultRole       = $request->input('role_default');
            $overwriteExisting = $request->input('overwrite_existing', false);

            $import = new UserImport($defaultRole, $overwriteExisting);
            Excel::import($import, $file);

            logActivity('user', 'Import users from file.');

            return redirect()->route('users.index')
                ->with('success', "Import completed successfully. Users have been added to the database.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing users: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Login as a specific user using laravel-impersonate.
     */
    public function loginAs(Request $request, $user)
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

        // Decrypt the user ID
        try {
            $userId     = decryptId($user);
            $targetUser = $this->userService->getUserById($userId);
        } catch (\Exception $e) {
            // Fallback if not encrypted (should not happen if consistent)
            $targetUser = $this->userService->getUserById($user);
        }

        if (! $targetUser) {
            abort(404);
        }

        // Use laravel-impersonate
        app('impersonate')->take(auth()->user(), $targetUser);

        logActivity('impersonation', 'User impersonated ' . $targetUser->name . ' (ID: ' . $targetUser->id . ')', $targetUser);

        return response()->json([
            'success'  => true,
            'message'  => 'Impersonation successful',
            'redirect' => route('dashboard'),
        ]);
    }

    /**
     * Leave impersonation and switch back to original user.
     */
    public function switchBack()
    {
        if (! app('impersonate')->isImpersonating()) {
            return redirect()->route('dashboard')->with('error', 'Not currently impersonating anyone.');
        }

        $impersonator = app('impersonate')->getImpersonator();

        app('impersonate')->leave();

        if ($impersonator) {
            logActivity('impersonation', 'User switched back from impersonation to original account', $impersonator);
        }

        return redirect()->route('dashboard')->with('success', 'Successfully switched back to original account.');
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
