<?php

namespace App\Http\Controllers;

use App\file_dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SarimaxController extends Controller
{
    protected $flaskUrl = 'http://localhost:5000'; // ganti jika Flask host berbeda

    public function train(Request $request)
    {
        $data = file_dataset::findOrFail($request->input('id', 1));

        // Ambil file dari storag
        $filePath = storage_path('app/public/dataset/' . $data->file_name);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File tidak ditemukan di server.'], 404);
        }


        // Kirim ke Flask
        $response = Http::timeout(10000) // 120 detik
            ->attach(
                'file',                                // HARUS "file" agar cocok dengan Flask route
                file_get_contents($filePath),
                basename($filePath)
            )->post("{$this->flaskUrl}/sarimax/train", [
                'file' => $data->file_name,
                'date_col' => $data->date_column,
                'sales_col' => $data->sales_column,
                'family_col' => $data->family_column,
                'family_val' => $request->input('family_val'),
            ]);

        return $response->successful()
            ? redirect()->route('dataset.index')->with('success', 'Model berhasil dilatih.')
            : response()->json(['error' => 'Flask training request failed'], 500);
    }

    public function predict(Request $request)
    {
        // Validasi input
        $request->validate([
            'family_val' => 'required|string',
        ]);

        // Kirim request ke Flask
        $response = Http::post("{$this->flaskUrl}/sarimax/predict", [
            'family_val' => $request->input('family_val'),
            'steps' => $request->input('steps', 30),
        ]);

        return $response->successful()
            ? response()->json($response->json())
            : response()->json(['error' => 'Flask prediction request failed'], 500);
    }
}
