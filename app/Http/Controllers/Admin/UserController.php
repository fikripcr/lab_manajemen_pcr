<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserExport;
use App\Http\Requests\Admin\UserRequest;
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
        $users = User::with('roles');

        return DataTables::of($users)
            ->addIndexColumn()
            ->editColumn('name', function ($user) {
                return '
                    <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <img src="' . $user->avatar_url . '"
                                 alt="' . $user->name . '" class="rounded-circle w-px-40 h-40">
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-nowrap">' . $user->name . '</span>
                            <small class="text-muted">' . $user->created_at . '</small>
                        </div>
                    </div>
                ';
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
                                <a href="javascript:void(0)" class="dropdown-item text-danger" onclick="confirmDelete(\'' . route('users.destroy', $encryptedId) . '\')">
                                    <i class="bx bx-trash me-1"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>';
            })
            ->rawColumns(['action'])
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
            'npm' => $validated['npm'] ?? null,
            'nip' => $validated['nip'] ?? null,
        ]);

        // Handle avatar upload using HasMedia trait
        if ($request->hasFile('avatar')) {
            $user->addMedia($request->file('avatar'), 'avatar');
        }

        // Assign the selected role to the user
        $user->assignRole($validated['role']);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $realId = decryptId($id);
        if (!$realId) {
            abort(404);
        }

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
        if (!$realId) {
            abort(404);
        }

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
        if (!$realId) {
            abort(404);
        }

        $user = User::findOrFail($realId);
        $validated = $request->validated();

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
            'npm' => $validated['npm'] ?? null,
            'nip' => $validated['nip'] ?? null,
        ];

        if (isset($validated['password'])) {
            $updatedData['password'] = Hash::make($validated['password']);
        }

        $user->update($updatedData);

        // Update the user's role
        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $realId = decryptId($id);
        if (!$realId) {
            abort(404);
        }

        $user = User::findOrFail($realId);
        $user->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
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
}
