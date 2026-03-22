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
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->unsignedInteger('pnp_datang_dewasa')->default(0)->after('eta');
            $table->unsignedInteger('pnp_datang_anak')->default(0)->after('pnp_datang_dewasa');
            $table->unsignedInteger('pnp_tolak_dewasa')->default(0)->after('pnp_datang_anak');
            $table->unsignedInteger('pnp_tolak_anak')->default(0)->after('pnp_tolak_dewasa');

            $table->unsignedInteger('kend_datang_gol1')->default(0)->after('motor_naik');
            $table->unsignedInteger('kend_datang_gol2')->default(0)->after('kend_datang_gol1');
            $table->unsignedInteger('kend_datang_gol3')->default(0)->after('kend_datang_gol2');
            $table->unsignedInteger('kend_datang_gol4a')->default(0)->after('kend_datang_gol3');
            $table->unsignedInteger('kend_datang_gol4b')->default(0)->after('kend_datang_gol4a');
            $table->unsignedInteger('kend_datang_gol5')->default(0)->after('kend_datang_gol4b');
            $table->unsignedInteger('kend_tolak_gol1')->default(0)->after('kend_datang_gol5');
            $table->unsignedInteger('kend_tolak_gol2')->default(0)->after('kend_tolak_gol1');
            $table->unsignedInteger('kend_tolak_gol3')->default(0)->after('kend_tolak_gol2');
            $table->unsignedInteger('kend_tolak_gol4a')->default(0)->after('kend_tolak_gol3');
            $table->unsignedInteger('kend_tolak_gol4b')->default(0)->after('kend_tolak_gol4a');
            $table->unsignedInteger('kend_tolak_gol5')->default(0)->after('kend_tolak_gol4b');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->dropColumn([
                'pnp_datang_dewasa',
                'pnp_datang_anak',
                'pnp_tolak_dewasa',
                'pnp_tolak_anak',
                'kend_datang_gol1',
                'kend_datang_gol2',
                'kend_datang_gol3',
                'kend_datang_gol4a',
                'kend_datang_gol4b',
                'kend_datang_gol5',
                'kend_tolak_gol1',
                'kend_tolak_gol2',
                'kend_tolak_gol3',
                'kend_tolak_gol4a',
                'kend_tolak_gol4b',
                'kend_tolak_gol5',
            ]);
        });
    }
};
