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
        Schema::table('jenis_tkk', function (Blueprint $table) {
            $table->string('kode_tkk', 50)->nullable()->after('id_jenis_tkk');
            $table->unsignedTinyInteger('nomor_klaster')->nullable()->after('id_jurusan')->comment('Nomor klaster skema TKK (1 s.d. 9)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_tkk', function (Blueprint $table) {
            $table->dropColumn(['kode_tkk', 'nomor_klaster']);
        });
    }
};
