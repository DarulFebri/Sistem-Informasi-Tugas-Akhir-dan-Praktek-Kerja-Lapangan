<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Hanya tambahkan kolom jika belum ada
        if (!Schema::hasColumn('users', 'email_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable();
            });
        }

        if (!Schema::hasColumn('users', 'verification_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('verification_token')->nullable();
            });
        }
    }

    public function down()
    {
        // Opsional: Hapus kolom jika diperlukan
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_verified_at', 'verification_token']);
        });
    }
};