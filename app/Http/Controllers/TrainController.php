<?php

namespace App\Http\Controllers;

use App\file_dataset;
use App\ModelDataset;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class TrainController extends Controller
{
    public function index()
    {
        $data = file_dataset::paginate(20);

        $model = ModelDataset::paginate(20);

        return view('prediksi.train', compact('data', 'model'));
    }

    public function stockView()
    {
        $models = ModelDataset::paginate(20);
        return view('prediksi.recomendation', compact('models'));
    }

    public function getRecommendation(Request $request)
    {
        try {
            $response = Http::post('http://localhost:5000/stock_recommendation', [
                'model_id' => $request->input('model_id')
            ]);

            if ($response->successful()) {
                $models = ModelDataset::all();

                $data = $response->json();

                // Simpan ke session agar bisa dipakai ulang
                $recommendationsRaw = $data['recommendations'] ?? [];
                $totalRecommendations = count($recommendationsRaw);

                // Buat pagination manual dari Collection
                $page = $request->get('page', 1);
                $perPage = 10;
                $collection = collect($recommendationsRaw);
                $paginatedRecommendations = new LengthAwarePaginator(
                    $collection->forPage($page, $perPage),
                    $totalRecommendations,
                    $perPage,
                    $page,
                    ['path' => request()->url(), 'query' => request()->query()]
                );

                return view('prediksi.recomendation', [
                    'recommendations' => $paginatedRecommendations,
                    'total_recommendations' => $totalRecommendations,
                    'models' => $models
                ]);
            } else {
                $error = $response->json('error') ?? 'Unknown error';
                return back()->with('error', $error);
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
