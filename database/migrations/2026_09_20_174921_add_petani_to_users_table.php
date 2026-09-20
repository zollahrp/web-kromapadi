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
        // First, alter the enum for MySQL to include 'petani'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'master_wilayah', 'ktd', 'petani') DEFAULT 'petani'");

        Schema::table('users', function (Blueprint $table) {
            // Add kelompok_tani_id reference
            $table->unsignedBigInteger('kelompok_tani_id')->nullable()->after('wilayah_id');
            // Assuming kelompok_tanis table exists, but we won't strictly enforce foreign key to avoid issues on cascade if needed, or we can. Let's just use column.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kelompok_tani_id');
        });
        
        // Revert enum back (might cause error if there are 'petani' rows, so just leave or ignore)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'master_wilayah', 'ktd') DEFAULT 'ktd'");
    }
};
