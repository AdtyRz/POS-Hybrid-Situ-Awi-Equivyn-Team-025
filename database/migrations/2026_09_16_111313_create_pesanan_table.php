<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meja_id')->constrained('meja_makan')->onDelete('cascade');
            $table->string('kode_pesanan')->unique();
            $table->unsignedBigInteger('total_bayar')->default(0);
            $table->enum('status_pesanan', ['menunggu', 'diproses', 'siap', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->enum('status_pembayaran', ['belum_bayar', 'sudah_bayar'])->default('belum_bayar');
            $table->timestamp('waktu_pesan')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
