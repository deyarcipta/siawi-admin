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
        if (!Schema::hasTable('guru_jurusan')) {
            Schema::create('guru_jurusan', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('id_guru')->index();
                $table->unsignedBigInteger('id_jurusan')->index();
                $table->string('role', 50)->default('kaprog'); // kaprog, guru_jurusan
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_jurusan');
    }
};
