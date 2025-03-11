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
        Schema::create('setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_app');
            $table->string('nama_sekolah');
            $table->string('nama_kepsek');
            $table->string('nip_kepsek');
            $table->string('alamat');
            $table->string('kel');
            $table->string('kec');
            $table->string('prov');
            $table->string('kota');
            $table->string('logo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting');
    }
};
