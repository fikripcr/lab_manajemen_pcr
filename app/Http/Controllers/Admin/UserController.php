<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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
            ->editColumn('roles', function ($user) {
                return $user->roles->pluck('name')->first() ?? 'No Role';
            })
            ->addColumn('action', function ($user) {
                $encryptedId = encryptId($user->id);
                return '
                    <div class="d-flex">
                        <a href="' . route('users.show', $encryptedId) . '" class="btn-info btn btn-sm me-1" title="View">
                            <i class="bx bx-show"></i>
                        </a>
                        <a href="' . route('users.edit', $encryptedId) . '" class="btn-primary btn btn-sm me-1" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                        <form action="' . route('users.destroy', $encryptedId) . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn-danger btn btn-sm" title="Delete" onclick="return confirm(\'Are you sure?\')">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
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

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'npm' => $validated['npm'] ?? null,
            'nip' => $validated['nip'] ?? null,
        ]);

        // Update the user's role
        $user->syncRoles([$validated['role']]);

        if (isset($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

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

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
