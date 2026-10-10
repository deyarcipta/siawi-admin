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
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('no_hp_ayah')->nullable()->after('penghasilan_ayah')->comment('Nomor WhatsApp / HP Ayah');
            $table->string('no_hp_ibu')->nullable()->after('penghasilan_ibu')->comment('Nomor WhatsApp / HP Ibu');
            $table->string('no_hp_wali')->nullable()->after('penghasilan_wali')->comment('Nomor WhatsApp / HP Wali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['no_hp_ayah', 'no_hp_ibu', 'no_hp_wali']);
        });
    }
};
