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
        Schema::create('kapals', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->enum('jenis', ['KLM', 'KM', 'KMP', 'MV'])->nullable();
            $table->decimal('gt', 10, 2)->nullable();
            $table->decimal('dwt', 10, 2)->nullable();
            $table->decimal('panjang', 10, 2)->nullable();
            $table->string('tanda_selar', 50)->nullable();
            $table->string('call_sign', 20)->nullable();
            $table->string('tempat_kedudukan', 100)->nullable();
            $table->string('bendera', 50)->default('INDONESIA');
            $table->string('pemilik_agen', 200)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kapals');
    }
};
