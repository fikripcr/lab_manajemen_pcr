<?php
namespace App\Services\Lab;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{

    protected function getUserById(int $id): Role
    {
        $user = User::find($id);
        if (! $user) {
            throw new \Exception("Users not found!");
        }
        return $user;
    }

    /**
     * Create a new user
     */
    public function createUser(array $data)
    {
        return DB::transaction(function () use ($data) {
            dd($data);
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make($data['password']),
                'email_verified_at' => now(),
            ]);

            // Assign roles if provided
            if (isset($data['roles']) && is_array($data['roles'])) {
                $user->assignRole($data['roles']);
            }

            // Set active role if provided
            if (isset($data['active_role'])) {
                session(['active_role' => $data['active_role']]);
            }

            // Handle avatar upload using Spatie Media Library
            // if ($request->hasFile('avatar')) {
            //     $user->addMedia($request->file('avatar'))->toMediaCollection('avatar');
            // }

            // Assign the selected role(s) to the user
            if (is_array($data['role'])) {
                $user->syncRoles($data['role']); // syncRoles replaces all roles with new set
            } else {
                $user->assignRole($data['role']);
            }

            logActivity('user_management', "Create new user: {$user->name}");
            return $user;
        });
    }

    /**
     * Update an existing role
     */
    public function updateUser(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $user = $this->getUserById($id);

            $oldName = $role->name;
            $role->update(['name' => $data['name']]);

            if (! empty($data['permissions']) && is_array($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            if ($oldName !== $role->name) {
                logActivity('role_management', "Mengubah nama role dari '{$oldName}' menjadi '{$role->name}'");
            } else {
                logActivity('role_management', "Memperbarui hak akses role: {$role->name}");
            }

            return true;
        });
    }

    public function deleteUser(int $id)
    {
        return DB::transaction(function () use ($id) {
            $user     = $this->getUserById($id);
            $userName = $user->name;
            $user->delete();

            logActivity('user_management', "Delete user: {$userName}");
            return true;
        });
    }

}
