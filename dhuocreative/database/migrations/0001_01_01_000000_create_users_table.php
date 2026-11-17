<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id_user')->primary();
            $table->string('nama', 100);
            $table->string('email', 150)->unique();
            $table->string('no_telp', 255);
            $table->string('pasword', 255);
            $table->enum('role', [
                'admin',
                'operator',
                'tentor',
                'siswa'
            ]);
            $table->integer('tahun_masuk');
            $table->enum('status', [
                'aktif',
                'nonaktif'
            ]);
            $table->timestamp('created_at');
            $table->timestamp('update_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
