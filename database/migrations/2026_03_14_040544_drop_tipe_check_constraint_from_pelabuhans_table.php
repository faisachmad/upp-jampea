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
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE pelabuhans DROP CONSTRAINT IF EXISTS pelabuhans_tipe_check');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to restore the specific check constraint without knowing the exact state,
        // and since we moved to string, it's generally safe to leave it dropped.
    }
};
