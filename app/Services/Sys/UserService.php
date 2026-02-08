<?php
namespace App\Services\Sys;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Get paginated list of users with optional filters
     */
    public function getUserList(array $filters = []): LengthAwarePaginator
    {
        return DB::transaction(function () use ($filters) {
            $query   = $this->getFilteredQuery($filters);
            $perPage = $filters['per_page'] ?? 10;
            return $query->paginate($perPage);
        });
    }

    /**
     * Get a specific user by ID
     */
    public function getUserById(int $userId): ?User
    {
        return User::with(['roles', 'media'])->find($userId);
    }

    /**
     * Create a new user
     */
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make($data['password']),
                'email_verified_at' => now(),
                'expired_at'        => $data['expired_at'] ?? null,
            ]);

            // Assign roles
            if (isset($data['roles']) && is_array($data['roles'])) {
                $user->assignRole($data['roles']); // assignRole accepts array or string
            } elseif (isset($data['role'])) {
                $user->assignRole($data['role']); // Controller passed 'role' (singular/array)
            }

            // Handle Avatar if provided
            if (isset($data['avatar']) && $data['avatar']) {
                $user->addMedia($data['avatar'])->toMediaCollection('avatar');
            }

            logActivity('user', 'Membuat pengguna baru: ' . $user->name, $user);

            return $user;
        });
    }

    /**
     * Update an existing user
     */
    public function updateUser(int $userId, array $data): bool
    {
        return DB::transaction(function () use ($userId, $data) {
            $user          = User::findOrFail($userId);
            $oldAttributes = $user->getAttributes();
            $oldName       = $user->name;

            // Update basic fields
            $updateData = [
                'name'  => $data['name'],
                'email' => $data['email'],
            ];

            if (isset($data['expired_at'])) {
                $updateData['expired_at'] = $data['expired_at'];
            }

            // Update password if provided
            if (! empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

                                                                       // Sync roles
            if (class_exists(\Spatie\Permission\Models\Role::class)) { // Safety check
                if (isset($data['roles'])) {
                    $user->syncRoles($data['roles']);
                } elseif (isset($data['role'])) {
                    $user->syncRoles($data['role']);
                }
            }

            // Handle Avatar
            if (isset($data['avatar']) && $data['avatar']) {
                $user->clearMediaCollection('avatar');
                $user->addMedia($data['avatar'])->toMediaCollection('avatar');
            }

            logActivity('user', 'Memperbarui pengguna ' . $user->name, $user, [
                'old'        => $oldAttributes,
                'attributes' => $user->getAttributes(),
            ]);

            return true;
        });
    }

    /**
     * Delete a user
     */
    public function deleteUser(int $userId): bool
    {
        return DB::transaction(function () use ($userId) {
            $user = User::findOrFail($userId);
            $name = $user->name;

            // Detach roles logic (optional as deleting user normally detaches in pivot, but explicit is fine)
            if ($user->roles()->count() > 0) {
                $user->roles()->detach();
            }

            $user->delete();

            logActivity('user', 'Menghapus pengguna: ' . $name);

            return true;
        });
    }

    /**
     * Get filtered query for DataTables and Exports
     */
    public function getFilteredQuery(array $filters = [])
    {
        $query = User::with(['roles', 'media'])
            ->select('users.*');

        // Handle DataTables search format (array with 'value' key)
        $searchValue = $filters['search'] ?? '';
        if (is_array($searchValue)) {
            $searchValue = $searchValue['value'] ?? '';
        }

        // Search (Name or Email)
        if (! empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('email', 'like', '%' . $searchValue . '%');
            });
        }

        // Exact filters
        if (! empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (! empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (! empty($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        // Date filters (for PDF export)
        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query;
    }
}
