<?php

namespace App\Http\Controllers;

use App\file_dataset;
use App\ModelDataset;
use Illuminate\Http\Request;
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
            $response = Http::post('https://3089-103-36-14-70.ngrok-free.app/stock_recommendation', [
                'model_id' => $request->input('model_id')
            ]);

            if ($response->successful()) {
                return back()->with('recommendations', $response->json());
            } else {
                $error = $response->json('error') ?? 'Unknown error';
                return back()->with('error', $error);
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
