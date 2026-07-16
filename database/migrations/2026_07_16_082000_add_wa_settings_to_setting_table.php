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
        Schema::table('setting', function (Blueprint $table) {
            $table->boolean('wa_status')->default(true)->after('logo');
            $table->string('wa_api_url')->nullable()->after('wa_status');
            $table->string('wa_api_key')->nullable()->after('wa_api_url');
            $table->string('wa_session_id')->nullable()->after('wa_api_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setting', function (Blueprint $table) {
            $table->dropColumn(['wa_status', 'wa_api_url', 'wa_api_key', 'wa_session_id']);
        });
    }
};
