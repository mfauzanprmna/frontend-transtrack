<?php

namespace App\Http\Controllers;

use App\file_dataset;
use App\ModelDataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class XGBoostController extends Controller
{
    protected $flaskUrl = 'http://localhost:5000'; // ganti jika Flask host berbeda

    public function train(Request $request)
    {
        $data = file_dataset::findOrFail($request->input('dataset_id', 1));

        // Ambil file dari storag
        $filePath = storage_path('app/public/datasets/train.csv');

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File tidak ditemukan di server.'], 404);
        }


        // Kirim ke Flask
        $response = Http::timeout(10000) // 120 detik
            ->attach(
                'file',                                // HARUS "file" agar cocok dengan Flask route
                file_get_contents($filePath),
                basename($filePath)
            )->post("{$this->flaskUrl}/train", [
                'file' => $data->file_name,
                'date_col' => $data->date_column,
                'sales_col' => $data->sales_column,
                'family_col' => $data->family_column,
                'store_col' => $data->store_column,
            ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Flask training request failed',
                'status' => $response->status(),
                'flask_response' => $response->body(),
            ], 500);
        }


        if ($response->successful()) {
            $data = $response->json();

            // Simpan model_id ke database
            ModelDataset::create([
                'dataset_id' => $request->input('dataset_id'),
                'model_name' => $data['model_id'],
                'family_name' => $request->input('family_val'),
                'metrics' => json_encode($data['metrics'])
            ]);

            return redirect()->route('dataset.index')->with('success', 'Model berhasil dilatih.');
        }
    }

    public function index()
    {
        $models = ModelDataset::paginate(20);

        return view('prediksi.restock', compact('models'));
    }

    public function predict(Request $request)
    {
        $model_id = request('model_id');
        $n_weeks = request('n_weeks', 2);

        $models = ModelDataset::all();

        try {
            $response = Http::post("{$this->flaskUrl}/forecast", [
                'model_id' => $model_id,
                'n_weeks' => $n_weeks
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $data = $result['forecast'];

                return view('prediksi.restock', compact('data', 'model_id', 'n_weeks', 'models'));
            } else {
                return back()->with('error', 'Forecast failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
