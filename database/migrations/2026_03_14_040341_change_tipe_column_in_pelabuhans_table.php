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
        Schema::table('pelabuhans', function (Blueprint $table) {
            $table->string('tipe', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelabuhans', function (Blueprint $table) {
            $table->enum('tipe', ['UPP', 'POSKER', 'WILKER', 'LUAR'])->change();
        });
    }
};
