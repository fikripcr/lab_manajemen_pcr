<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow
{
    protected $roles;
    protected $defaultRole;
    protected $overwriteExisting;

    public function __construct($defaultRole = null, $overwriteExisting = false)
    {
        $this->roles = Role::all();
        $this->defaultRole = $defaultRole;
        $this->overwriteExisting = $overwriteExisting;
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        // Prepare all data to be inserted
        $users = [];
        $defaultPassword = Hash::make('password'); // Default password for imported users

        foreach ($rows as $index => $row) {
            // Ensure all required keys exist
            if (
                !isset($row['name']) ||
                !isset($row['email'])
            ) {
                throw new \Exception("Kolom Excel tidak sesuai template. Kolom yang diperlukan: name, email. Kolom tambahan: role, password.");
            }

            // Validate email format
            if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                throw new \Exception("Email pada baris " . ($index + 1) . " tidak valid: {$row['email']}");
            }

            // Add data to collection
            $users[] = [
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => !empty($row['password']) ? Hash::make($row['password']) : $defaultPassword,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Check if overwriting existing users is allowed
        if ($this->overwriteExisting) {
            // For overwriting, we need to process row by row
            foreach ($users as $user) {
                $existingUser = User::where('email', $user['email'])->first();

                // Find the corresponding row in the original data
                $originalRow = null;
                foreach ($rows as $row) {
                    if ($row['email'] === $user['email']) {
                        $originalRow = $row;
                        break;
                    }
                }

                if ($existingUser) {
                    // Update existing user
                    $updateData = [
                        'name' => $user['name'],
                        'email_verified_at' => $user['email_verified_at'],
                    ];

                    // Update password if provided in row
                    if (!empty($originalRow['password'])) {
                        $updateData['password'] = $user['password'];
                    }

                    $existingUser->update($updateData);

                    // Assign role if specified in the file or use default
                    $roleName = !empty($originalRow['role']) ? $originalRow['role'] : $this->defaultRole;
                    if ($roleName) {
                        $role = $this->roles->firstWhere('name', $roleName);
                        if ($role) {
                            $existingUser->syncRoles([$role->name]);
                        } else {
                            // If role doesn't exist, create it
                            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName]);
                            $existingUser->assignRole($role);
                        }
                    }
                } else {
                    // Create new user
                    $newUser = User::create([
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'password' => $user['password'],
                        'email_verified_at' => $user['email_verified_at'],
                    ]);

                    // Assign role if specified in the file or use default
                    $roleName = !empty($originalRow['role']) ? $originalRow['role'] : $this->defaultRole;
                    if ($roleName) {
                        $role = $this->roles->firstWhere('name', $roleName);
                        if ($role) {
                            $newUser->assignRole($role);
                        } else {
                            // If role doesn't exist, create it
                            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName]);
                            $newUser->assignRole($role);
                        }
                    }
                }
            }
        } else {
            // Only insert new users (skip existing emails)
            $existingEmails = User::whereIn('email', array_column($users, 'email'))->pluck('email')->toArray();

            $filteredUsers = [];
            $originalRowsForNewUsers = [];

            foreach ($users as $index => $user) {
                if (!in_array($user['email'], $existingEmails)) {
                    $filteredUsers[] = $user;
                    $originalRowsForNewUsers[] = $rows->get($index);
                }
            }

            // Perform bulk insert for new users only
            if (!empty($filteredUsers)) {
                User::insert(array_values($filteredUsers));
            }

            // Now assign roles to the newly created users
            $newEmails = array_column($filteredUsers, 'email');
            $createdUsers = User::whereIn('email', $newEmails)->get();

            foreach ($rows as $index => $row) {
                if (in_array($row['email'], $newEmails)) { // Only for new users
                    $createdUser = $createdUsers->firstWhere('email', $row['email']);
                    if ($createdUser) {
                        $roleName = !empty($row['role']) ? $row['role'] : $this->defaultRole;
                        if ($roleName) {
                            $role = $this->roles->firstWhere('name', $roleName);
                            if ($role) {
                                $createdUser->assignRole($role);
                            } else {
                                // If role doesn't exist, create it
                                $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName]);
                                $createdUser->assignRole($role);
                            }
                        }
                    }
                }
            }
        }
    }

    public function headingRow(): int
    {
        return 1; // Header is in the first row
    }
}
