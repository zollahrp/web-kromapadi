<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Expand ENUM to allow both temporary during migration
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'master_wilayah', 'penyuluh', 'ktd', 'petani') DEFAULT 'petani'");

        // 2. Update existing records with 'master_wilayah' to 'penyuluh'
        DB::table('users')->where('role', 'master_wilayah')->update(['role' => 'penyuluh']);

        // 3. Update default demo user name if named 'Master Wilayah Mulyaharja'
        DB::table('users')->where('name', 'Master Wilayah Mulyaharja')->update(['name' => 'Penyuluh Mulyaharja']);

        // 4. Narrow ENUM to remove 'master_wilayah' completely
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'penyuluh', 'ktd', 'petani') DEFAULT 'petani'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'master_wilayah', 'penyuluh', 'ktd', 'petani') DEFAULT 'petani'");

        DB::table('users')->where('role', 'penyuluh')->update(['role' => 'master_wilayah']);
        DB::table('users')->where('name', 'Penyuluh Mulyaharja')->update(['name' => 'Master Wilayah Mulyaharja']);

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'master_wilayah', 'ktd', 'petani') DEFAULT 'petani'");
    }
};
