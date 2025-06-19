<?php

namespace App\Http\Controllers;

use App\ModelDataset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{

    protected $flaskUrl;

    public function __construct()
    {
        $this->flaskUrl = config('ml.flask_url');
    }
    public function index()
    {
        $models = ModelDataset::paginate(20);
        return view('prediksi.deepseek', compact('models'));
    }

    public function askDeepSeek(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'model_id' => 'nullable|string',
            'format' => 'nullable|in:summary,records',
        ]);

        try {
            $response = Http::timeout(1000) // Set timeout sesuai kebutuhan
                ->post("{$this->flaskUrl}/qa", [
                    'question' => $validated['question'],
                    'model_id' => $validated['model_id'] ?? null,
                    'format' => $validated['format'] ?? 'records',
                ]);

            if ($response->failed()) {
                return back()->with('error', $response->body());
            }

            return view('prediksi.deepseek', [
                'qa_response' => $response->json(),
                'models' => ModelDataset::all(), // Sesuaikan
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
