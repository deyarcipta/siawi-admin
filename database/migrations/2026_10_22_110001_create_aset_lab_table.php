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
        Schema::create('aset_lab', function (Blueprint $table) {
            $table->id('id_aset');
            $table->unsignedBigInteger('id_lab');
            $table->string('kode_aset', 50)->unique();
            $table->string('nama_aset');
            $table->enum('kategori', ['alat', 'bahan_habis_pakai'])->default('alat');
            $table->text('spesifikasi')->nullable();
            $table->integer('jumlah')->default(1);
            $table->integer('stok_tersedia')->default(1);
            $table->integer('stok_minimum')->default(5);
            $table->string('satuan', 50)->default('Unit');
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->year('tahun_perolehan');
            $table->string('sumber_dana', 100)->nullable();
            $table->decimal('harga_satuan', 12, 2)->default(0.00);
            $table->string('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_lab')->references('id_lab')->on('lab')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aset_lab');
    }
};
