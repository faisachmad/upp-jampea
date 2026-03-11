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
        Schema::create('kunjungan_muatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans')->onDelete('cascade');
            $table->enum('tipe', ['BONGKAR', 'MUAT']);
            $table->string('jenis_barang', 100)->nullable();
            $table->decimal('ton_m3', 10, 2)->default(0);
            $table->string('jenis_hewan', 50)->nullable();
            $table->integer('jumlah_hewan')->default(0);
            $table->timestamps();

            $table->index('kunjungan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan_muatans');
    }
};
