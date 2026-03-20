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
        Schema::table('kapals', function (Blueprint $table) {
            // Drop old jenis enum column and add foreign key
            $table->dropColumn('jenis');
            $table->foreignId('jenis_kapal_id')->nullable()->after('nama')->constrained('jenis_kapals')->nullOnDelete();

            // Drop old bendera string column and add foreign key
            $table->dropColumn('bendera');
            $table->foreignId('bendera_id')->nullable()->after('tempat_kedudukan')->constrained('benderas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kapals', function (Blueprint $table) {
            // Restore original structure
            $table->dropForeign(['jenis_kapal_id']);
            $table->dropColumn('jenis_kapal_id');
            $table->enum('jenis', ['KLM', 'KM', 'KMP', 'MV'])->nullable()->after('nama');

            $table->dropForeign(['bendera_id']);
            $table->dropColumn('bendera_id');
            $table->string('bendera', 50)->default('INDONESIA')->after('tempat_kedudukan');
        });
    }
};
