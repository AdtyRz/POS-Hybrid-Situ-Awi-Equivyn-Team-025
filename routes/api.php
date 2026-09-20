<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\LogPanggilPelayan;
use App\Models\MejaMakan;

// Endpoint yang di-hit oleh ESP32 saat tombol ditekan
Route::post('/iot/panggil-pelayan', function (Request $request) {
    $request->validate([
        'nomor_meja' => 'required',
        'jenis_panggilan' => 'required|in:service,bill' // service = panggil pelayan, bill = minta nota
    ]);

    // Cari meja berdasarkan nomor atau ID
    $meja = MejaMakan::where('nomor_meja', $request->nomor_meja)->first();

    if (!$meja) {
        return response()->json(['status' => 'error', 'message' => 'Meja tidak ditemukan'], 404);
    }

    // Simpan ke Log Panggil Pelayan
    $log = LogPanggilPelayan::create([
        'id_meja' => $meja->id,
        'jenis' => $request->jenis_panggilan,
        'status' => 'pending' // pending sebelum di-klik selesai oleh kasir/pelayan
    ]);

    // (Opsional) Di sini nanti bisa ditambahkan trigger Broadcast WebSocket (Reverb) agar layar kasir berbunyi *real-time*

    return response()->json([
        'status' => 'success',
        'message' => 'Panggilan dari meja berhasil dikirim ke server.',
        'data' => $log
    ], 200);
});
