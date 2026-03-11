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
        Schema::create('kunjungan_b3s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungans')->onDelete('cascade');
            $table->foreignId('barang_b3_id')->nullable()->constrained('barang_b3s')->onDelete('set null');
            $table->enum('jenis_kegiatan', ['BONGKAR', 'MUAT'])->nullable();
            $table->enum('bentuk_muatan', ['CURAH', 'PADAT'])->nullable();
            $table->decimal('jumlah_ton', 10, 2)->default(0);
            $table->integer('jumlah_container')->default(0);
            $table->string('kemasan', 50)->nullable();
            $table->integer('jumlah')->default(0);
            $table->string('petugas', 100)->nullable();
            $table->timestamps();

            $table->index('kunjungan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan_b3s');
    }
};
