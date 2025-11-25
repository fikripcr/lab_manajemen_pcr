<?php

namespace App\Services\Sys;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * Get paginated list of users with optional filters
     */
    public function getUserList(array $filters = []): LengthAwarePaginator
    {
        return DB::transaction(function() use ($filters) {
            $query = User::with(['roles', 'media']);

            // Apply filters
            if (isset($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            if (isset($filters['email'])) {
                $query->where('email', 'like', '%' . $filters['email'] . '%');
            }

            if (isset($filters['role'])) {
                $query->whereHas('roles', function($q) use ($filters) {
                    $q->where('name', $filters['role']);
                });
            }

            $perPage = $filters['per_page'] ?? 10;

            return $query->latest()->paginate($perPage);
        });
    }

    /**
     * Get a specific user by ID
     */
    public function getUserById(int $userId): ?User
    {
        return DB::transaction(function() use ($userId) {
            return User::with(['roles', 'media'])->find($userId);
        });
    }

    /**
     * Create a new user
     */
    public function createUser(array $data): User
    {
        return DB::transaction(function() use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
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

            return $user;
        });
    }

    /**
     * Update an existing user
     */
    public function updateUser(int $userId, array $data): bool
    {
        return DB::transaction(function() use ($userId, $data) {
            $user = User::find($userId);
            if (!$user) {
                return false;
            }

            // Update basic user info
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            // Update password if provided
            if (!empty($data['password'])) {
                $user->update([
                    'password' => Hash::make($data['password'])
                ]);
            }

            // Sync roles if provided
            if (isset($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            return true;
        });
    }

    /**
     * Delete a user
     */
    public function deleteUser(int $userId): bool
    {
        return DB::transaction(function() use ($userId) {
            $user = User::find($userId);
            if (!$user) {
                return false;
            }

            // Check if user has related data that would prevent deletion
            if ($user->roles()->count() > 0) {
                $user->removeRole($user->roles->pluck('name')->toArray());
            }

            return $user->delete();
        });
    }

    /**
     * Get filtered query for DataTables
     */
    public function getFilteredQuery(array $filters = [])
    {
        $query = User::with(['roles', 'media'])
            ->select('users.*')
            ->orderBy('users.created_at', 'desc');

        // Apply filters
        if (isset($filters['name'])) {
            $query->where('users.name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['email'])) {
            $query->where('users.email', 'like', '%' . $filters['email'] . '%');
        }

        if (isset($filters['role'])) {
            $query->whereHas('roles', function($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        return $query;
    }

    /**
     * Count users with filters
     */
    public function countUsers(array $filters = []): int
    {
        return DB::transaction(function() use ($filters) {
            $query = User::query();

            if (isset($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            if (isset($filters['email'])) {
                $query->where('email', 'like', '%' . $filters['email'] . '%');
            }

            if (isset($filters['role'])) {
                $query->whereHas('roles', function($q) use ($filters) {
                    $q->where('name', $filters['role']);
                });
            }

            return $query->count();
        });
    }
}