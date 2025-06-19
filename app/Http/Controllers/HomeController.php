<?php

namespace App\Http\Controllers;

use App\ModelDataset;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $flaskUrl;

    public function __construct()
    {
        $this->middleware('auth');
        $this->flaskUrl = config('ml.flask_url');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $users = User::count();
        $models = ModelDataset::all();

        $widget = [
            'users' => $users,
            // ...
        ];

        return view('home', [
            'widget' => $widget,
            'models' => $models,
        ]);
    }

    public function predictAndRenderFamilyChart(Request $request)
    {
        $model_id = $request->input('model_id');
        $n_weeks = $request->input('n_weeks', 4); // Default to 4 weeks if not provided

        // if (!$model_id) {
        //     return back()->with('error', 'Model ID is required.');
        // }

        $users = User::count();
        $models = ModelDataset::all();

        $widget = [
            'users' => $users,
            // ...
        ];

        try {
            $response = Http::timeout(1000000) // Set timeout sesuai kebutuhan
                ->post("{$this->flaskUrl}/forecast", [
                    'model_id' => $model_id,
                    'n_weeks' => $n_weeks
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $data = collect($result['forecast']);

                // Buat labels (tanggal)
                $labels = $data->pluck('date')->unique()->sort()->values();

                // Kelompokkan berdasarkan family
                $groupedByFamily = $data->groupBy('family');

                // Buat dataset per family
                $datasets = $groupedByFamily->map(function ($items, $family) use ($labels) {
                    // Total forecast per tanggal
                    $forecastPerDate = $items->groupBy('date')->map(function ($rows) {
                        return round($rows->sum('forecast'));
                    });

                    // Susun data sesuai urutan labels
                    $data = $labels->map(function ($date) use ($forecastPerDate) {
                        return $forecastPerDate->get($date, 0);
                    });

                    return [
                        'label' => $family,
                        'data' => $data,
                        'tension' => 0.4,
                        'fill' => false
                    ];
                })->values();

                return view('home', [
                    'labels' => $labels,
                    'datasets' => $datasets,
                    'model_id' => $model_id,
                    'n_weeks' => $n_weeks,
                    'widget' => $widget,
                    'models' => $models,
                ]);
            }

            return back()->with('error', 'Forecast failed: ' . $response->body());
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
