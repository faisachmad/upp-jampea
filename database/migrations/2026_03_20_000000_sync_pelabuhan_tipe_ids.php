<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tipeMap = DB::table('tipe_pelabuhans')->pluck('id', 'nama');

        foreach ($tipeMap as $nama => $id) {
            DB::table('pelabuhans')
                ->where('tipe', $nama)
                ->whereNull('tipe_pelabuhan_id')
                ->update(['tipe_pelabuhan_id' => $id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed as this is a data sync
    }
};
