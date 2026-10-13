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
        Schema::create('orang_tua_fcm_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_orang_tua');
            $table->text('fcm_token');
            $table->string('token_hash', 64)->nullable()->index();
            $table->string('device_name')->nullable();
            $table->timestamps();

            $table->index('id_orang_tua');
            $table->foreign('id_orang_tua')->references('id_orang_tua')->on('orang_tua')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orang_tua_fcm_tokens');
    }
};
