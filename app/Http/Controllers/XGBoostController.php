<?php

namespace App\Http\Controllers;

use App\file_dataset;
use App\ModelDataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class XGBoostController extends Controller
{
    protected $flaskUrl;

    public function __construct()
    {
        $this->flaskUrl = config('ml.flask_url');
    }

    public function train(Request $request)
    {
        $request->validate([
            'date_col' => 'required|string',
            'sales_col' => 'required|string',
            'store_col' => 'required|string',
            'family_col' => 'required|string',
        ]);

        $dataset = [
            'file_name' => $request->input('name'),
            'file_path' => $request->input('file_path'),
            'date_column' => $request->input('date_col'),
            'sales_column' => $request->input('sales_col'),
            'store_column' =>  $request->input('store_col'),
            'family_column' => $request->input('family_col'),
        ];

        // Simpan data ke database
        $file_dataset = file_dataset::create($dataset);

        // Ambil file dari storag
        $filePath = storage_path('app/public/datasets/' . $request->input('name') . '.csv');

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
                'file' => $request->name,
                'date_col' => $request->date_col,
                'sales_col' => $request->sales_col,
                'family_col' => $request->family_col,
                'store_col' => $request->store_col,
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
                'dataset_id' => $file_dataset->id,
                'model_name' => $data['model_id'],
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
            $response = Http::timeout(1000000) // 120 detik
                ->post("{$this->flaskUrl}/forecast", [
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
