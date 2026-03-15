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
        Schema::table('pelabuhans', function (Blueprint $table) {
            $table->foreignId('tipe_pelabuhan_id')->nullable()->constrained('tipe_pelabuhans')->onDelete('set null');
        });

        // Migrate existing data based on the 'tipe' string column
        $types = [
            'UPP' => 'Unit Penyelenggara Pelabuhan',
            'POSKER' => 'Pos Pengawasan Kepelabuanan',
            'WILKER' => 'Wilayah Kerja',
            'LUAR' => 'Pelabuhan Luar Wilayah',
        ];

        foreach ($types as $nama => $keterangan) {
            $typeId = DB::table('tipe_pelabuhans')->insertGetId([
                'nama' => $nama,
                'keterangan' => $keterangan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('pelabuhans')
                ->where('tipe', $nama)
                ->update(['tipe_pelabuhan_id' => $typeId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelabuhans', function (Blueprint $table) {
            $table->dropForeign(['tipe_pelabuhan_id']);
            $table->dropColumn('tipe_pelabuhan_id');
        });
    }
};
