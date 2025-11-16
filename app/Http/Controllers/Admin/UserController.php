<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserExport;
use App\Http\Requests\Admin\UserRequest;
use App\Imports\UserImport;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $search = $request->input('search');

        // $users = User::with('roles')->when($search, function ($query, $search) {
        //         return $query->where('name', 'like', "%{$search}%")
        //             ->orWhere('email', 'like', "%{$search}%");
        //     })
        //     ->paginate(20);

        return view('pages.admin.users.index');
    }

    /**
     * Process datatables ajax request.
     */
    public function data(Request $request)
    {
        $users = User::with('roles')->whereNull('deleted_at');

        return DataTables::of($users)
            ->addIndexColumn()
            ->editColumn('name', function ($user) {
                // Ensure we're getting the actual user name, not processed content
                $userName = htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8');
                $userCreatedAt = htmlspecialchars($user->created_at, ENT_QUOTES, 'UTF-8');

                $html = '<div class="d-flex align-items-center">';
                $html .= '<div class="avatar flex-shrink-0 me-3">';
                $html .= '<img src="' . $user->avatar_url . '" alt="' . $userName . '" class="rounded-circle w-px-40 h-40">';
                $html .= '</div>';
                $html .= '<div class="d-flex flex-column">';
                $html .= '<span class="text-nowrap">' . $userName . '</span>';
                $html .= '<small class="text-muted">' . $userCreatedAt . '</small>';
                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })
            ->editColumn('roles', function ($user) {
                return $user->roles->pluck('name')->first() ?? 'No Role';
            })
            ->addColumn('action', function ($user) {
                $encryptedId = encryptId($user->id);
                return '
                    <div class="d-flex align-items-center">
                        <a class="text-success me-2" href="' . route('users.edit', $encryptedId) . '" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-icon btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('users.show', $encryptedId) . '">
                                    <i class="bx bx-show me-1"></i> View
                                </a>
                                <a href="javascript:void(0)" class="dropdown-item" onclick="sendNotificationToUser(\'' . route('users.send.notification', $encryptedId) . '\', \'' . addslashes($user->name) . '\')">
                                    <i class="bx bx-bell me-1"></i> Test Kirim Notifikasi
                                </a>
                                <a href="javascript:void(0)" class="dropdown-item" onclick="loginAsUser(\'' . route('users.login.as', $encryptedId) . '\', \'' . addslashes($user->name) . '\')">
                                    <i class="bx bx-log-in me-1"></i> Login As
                                </a>
                                <a href="javascript:void(0)" class="dropdown-item text-danger" onclick="confirmDelete(\'' . route('users.destroy', $encryptedId) . '\')">
                                    <i class="bx bx-trash me-1"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>';
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
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
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'nim' => $validated['nim'] ?? null,
            'nip' => $validated['nip'] ?? null,
        ]);

        // Handle avatar upload using HasMedia trait
        if ($request->hasFile('avatar')) {
            $user->addMedia($request->file('avatar'), 'avatar');
        }

        // Assign the selected role to the user
        $user->assignRole($validated['role']);

        // Log the activity
        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log('Membuat pengguna '. $user->name);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $realId = decryptId($id);

        $user = User::findOrFail($realId);
        $user->id = encryptId($user->id);
        return view('pages.admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $realId = decryptId($id);

        $user = User::findOrFail($realId);
        $roles = Role::all();
        return view('pages.admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $id)
    {
        $realId = decryptId($id);

        $user = User::findOrFail($realId);
        $validated = $request->validated();

        // Store old values for logging
        $oldAttributes = $user->getAttributes();

        // Handle avatar upload using HasMedia trait
        if ($request->hasFile('avatar')) {
            // Clear existing avatar media
            $user->clearMediaCollection('avatar');
            // Add new avatar
            $user->addMedia($request->file('avatar'), 'avatar');
        }

        $updatedData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nim' => $validated['nim'] ?? null,
            'nip' => $validated['nip'] ?? null,
        ];

        if (isset($validated['password'])) {
            $updatedData['password'] = Hash::make($validated['password']);
        }

        $user->update($updatedData);

        // Update the user's role
        $user->syncRoles([$validated['role']]);

        // Log the activity
        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties([
                'old' => $oldAttributes,
                'attributes' => $user->getAttributes()
            ])
            ->log('Memperbarui pengguna '. $user->name);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $realId = decryptId($id);

        $user = User::findOrFail($realId);

        // Log the activity before deletion
        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log('Menghapus pengguna '. $user->name);

        $user->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dihapus.'
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Export users to Excel
     */
    public function export(Request $request)
    {
        // Extract filters from request (matching the DataTables filters)
        $filters = [
            'search' => $request->get('search'),
        ];
        $columns = $request->get('columns', ['id', 'name', 'email', 'role_name', 'npm', 'nip']);

        $export = new UserExport($filters, $columns);

        return Excel::download($export, 'users_' . date('Y-m-d_H-i-s') . '.xlsx');
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
            $defaultRole = $request->input('role_default');
            $overwriteExisting = $request->input('overwrite_existing', false);

            $import = new UserImport($defaultRole, $overwriteExisting);
            Excel::import($import, $file);

            return redirect()->route('users.index')
                ->with('success', "Import completed successfully. Users have been added to the database.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing users: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Login as a specific user.
     */
    public function loginAs(Request $request, $user)
    {
        // Only allow high-privilege users to use this feature
        $allowedRoles = ['admin', 'kepala_lab', 'ketua_jurusan']; // Allow these roles to login as other users
        $hasPermission = false;

        foreach ($allowedRoles as $role) {
            if (auth()->user()->hasRole($role)) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            abort(403, 'Unauthorized to use this feature.');
        }

        // Decrypt the user ID if necessary
        try {
            $userId = decryptId($user);
            $targetUser = User::findOrFail($userId);
        } catch (\Exception $e) {
            $targetUser = User::findOrFail($user);
        }

        // Store the original user ID in session to allow switching back
        session(['original_user_id' => auth()->user()->id]);

        // Login as the target user
        auth()->login($targetUser);

        return response()->json([
            'success' => true,
            'message' => 'Login sebagai ' . $targetUser->name . ' berhasil!',
            'redirect' => route('dashboard')
        ]);
    }

    /**
     * Switch back to original user account.
     */
    public function switchBack()
    {
        $originalUserId = session('original_user_id');

        if (!$originalUserId) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada akun asli untuk dikembalikan.');
        }

        // Find the original user
        $originalUser = User::findOrFail($originalUserId);

        // Login back as the original user
        auth()->login($originalUser);

        // Remove the session
        session()->forget('original_user_id');

        return redirect()->route('dashboard')->with('success', 'Berhasil kembali ke akun asli.');
    }
}
