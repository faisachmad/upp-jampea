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
        Schema::create('barang_b3s', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->integer('un_number')->nullable();
            $table->string('kelas', 10)->nullable();
            $table->string('kategori', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_b3s');
    }
};
