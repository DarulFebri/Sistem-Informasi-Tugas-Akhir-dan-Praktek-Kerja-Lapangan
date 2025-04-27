<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'dosen', 'mahasiswa'])->default('mahasiswa');
            $table->string('nomor_induk')->unique();
            $table->string('jurusan')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('kelas')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('kompetensi')->nullable();
            $table->boolean('ketersediaan')->default(true);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'nomor_induk', 'jurusan', 
                'program_studi', 'kelas', 
                'jenis_kelamin', 'kompetensi', 'ketersediaan'
            ]);
        });
    }
};