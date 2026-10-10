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
        // 1. Buat tabel orang_tua untuk kredensial mandiri
        Schema::create('orang_tua', function (Blueprint $table) {
            $table->bigIncrements('id_orang_tua');
            $table->string('username')->unique()->comment('Nomor WhatsApp atau username login orang tua');
            $table->string('password');
            $table->string('nama_lengkap')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Tambahkan foreign key id_orang_tua pada tabel siswa
        Schema::table('siswa', function (Blueprint $table) {
            $table->unsignedBigInteger('id_orang_tua')->nullable()->after('id_siswa');
            $table->foreign('id_orang_tua')->references('id_orang_tua')->on('orang_tua')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropForeign(['id_orang_tua']);
            $table->dropColumn('id_orang_tua');
        });

        Schema::dropIfExists('orang_tua');
    }
};
