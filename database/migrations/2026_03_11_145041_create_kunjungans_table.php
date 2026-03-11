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
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();

            // Referensi
            $table->foreignId('pelabuhan_id')->constrained('pelabuhans')->onDelete('cascade');
            $table->foreignId('kapal_id')->constrained('kapals')->onDelete('cascade');
            $table->foreignId('jenis_pelayaran_id')->constrained('jenis_pelayarans')->onDelete('cascade');
            $table->foreignId('nakhoda_id')->nullable()->constrained('nakhodas')->onDelete('set null');

            // Periode
            $table->integer('bulan');
            $table->integer('tahun');

            // TIBA
            $table->date('tgl_tiba')->nullable();
            $table->time('jam_tiba')->nullable();
            $table->foreignId('pelabuhan_asal_id')->nullable()->constrained('pelabuhans')->onDelete('set null');
            $table->enum('status_muatan_tiba', ['M', 'K', 'ML'])->nullable();

            // TAMBAT
            $table->date('tgl_tambat')->nullable();
            $table->time('jam_tambat')->nullable();

            // BERANGKAT
            $table->date('tgl_berangkat')->nullable();
            $table->time('jam_berangkat')->nullable();
            $table->foreignId('pelabuhan_tujuan_id')->nullable()->constrained('pelabuhans')->onDelete('set null');
            $table->enum('status_muatan_tolak', ['M', 'K', 'ML'])->nullable();

            // SPB
            $table->string('no_spb_tiba', 50)->nullable();
            $table->string('no_spb_tolak', 50)->nullable();
            $table->date('eta')->nullable();

            // PENUMPANG
            $table->integer('penumpang_turun')->default(0);
            $table->integer('penumpang_naik')->default(0);

            // KENDARAAN (Ferry)
            $table->integer('mobil_turun')->default(0);
            $table->integer('mobil_naik')->default(0);
            $table->integer('motor_turun')->default(0);
            $table->integer('motor_naik')->default(0);

            // MUATAN LANJUTAN
            $table->string('lanjutan_jenis', 100)->nullable();
            $table->decimal('lanjutan_ton', 10, 2)->default(0);
            $table->integer('lanjutan_mobil')->default(0);
            $table->integer('lanjutan_motor')->default(0);
            $table->integer('lanjutan_penumpang')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['tahun', 'bulan']);
            $table->index('pelabuhan_id');
            $table->index('jenis_pelayaran_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
