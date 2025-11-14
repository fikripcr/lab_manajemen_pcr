<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add the new nim column
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim')->nullable()->after('email');
        });

        // Copy data from npm to nim
        \DB::statement('UPDATE users SET nim = npm WHERE npm IS NOT NULL');

        // First, drop the unique constraint on npm column if it exists
        // We need to use raw SQL to drop the constraint since we don't know the exact constraint name
        try {
            // Get the constraint name for npm unique index
            $constraintResult = \DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'users'
                AND COLUMN_NAME = 'npm'
                AND REFERENCED_TABLE_NAME IS NULL
            ");

            if (!empty($constraintResult)) {
                $constraintName = $constraintResult[0]->CONSTRAINT_NAME;
                \DB::statement("ALTER TABLE users DROP INDEX `{$constraintName}`");
            }
        } catch (\Exception $e) {
            // If there's an issue getting or dropping the constraint, continue
        }

        // Now drop the npm column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('npm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the old npm column
        Schema::table('users', function (Blueprint $table) {
            $table->string('npm')->nullable()->after('email')->unique();
        });

        // Copy data from nim to npm
        \DB::statement('UPDATE users SET npm = nim WHERE nim IS NOT NULL');

        // Drop the nim column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nim');
        });
    }
};
