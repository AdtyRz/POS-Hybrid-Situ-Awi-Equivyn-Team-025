<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meja_makan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_meja');
            $table->string('kode_qr_token')->unique();
            $table->string('lokasi_area');
            $table->boolean('status_aktif')->default(true);
            $table->string('id_device')->unique()->nullable();
            $table->enum('status_meja', ['kosong', 'terisi'])->default('kosong');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meja_makan');
    }
};
