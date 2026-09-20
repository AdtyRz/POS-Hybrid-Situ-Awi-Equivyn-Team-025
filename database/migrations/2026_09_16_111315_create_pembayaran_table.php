<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('metode_pembayaran', ['tunai', 'qris', 'debit', 'kredit']);
            $table->string('snap_token')->nullable();
            $table->string('status_pembayaran')->default('pending');
            $table->unsignedBigInteger('jumlah_diterima')->default(0);
            $table->unsignedBigInteger('kembalian')->default(0);
            $table->timestamp('waktu_bayar')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
