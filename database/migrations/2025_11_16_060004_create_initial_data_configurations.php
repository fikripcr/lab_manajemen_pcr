<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations for initial data and configurations.
     */
    public function up(): void
    {
        // Insert default roles if they don't exist
        $roles = [
            'admin', 'penanggung_jawab_lab', 'penyelenggara_kegiatan', 
            'teknisi', 'dosen', 'kepala_lab', 'ketua_jurusan', 'mahasiswa'
        ];
        
        foreach ($roles as $role) {
            if (!DB::table('roles')->where('name', $role)->exists()) {
                DB::table('roles')->insert([
                    'name' => $role,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Insert a default admin user if none exists
        if (!DB::table('users')->where('email', 'admin@example.com')->exists()) {
            $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
            
            $userId = DB::table('users')->insertGetId([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Assign admin role to the user
            DB::table('model_has_roles')->insert([
                'role_id' => $adminRoleId,
                'model_type' => 'App\Models\User',
                'model_id' => $userId
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the default admin user and role assignments
        $adminUser = DB::table('users')->where('email', 'admin@example.com')->first();
        if ($adminUser) {
            DB::table('model_has_roles')->where('model_id', $adminUser->id)->delete();
            DB::table('users')->where('email', 'admin@example.com')->delete();
        }
        
        // Remove the default roles
        DB::table('roles')->whereIn('name', [
            'admin', 'penanggung_jawab_lab', 'penyelenggara_kegiatan', 
            'teknisi', 'dosen', 'kepala_lab', 'ketua_jurusan', 'mahasiswa'
        ])->delete();
    }
};