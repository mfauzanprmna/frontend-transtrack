<?php

namespace App\Http\Controllers;

use App\ModelDataset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    public function index()
    {
        $models = ModelDataset::paginate(20);
        return view('prediksi.deepseek', compact('models'));
    }

    protected $flaskUrl = 'http://localhost:5000'; // ganti jika Flask host berbeda
    public function askDeepSeek(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'model_id' => 'nullable|string',
            'format' => 'nullable|in:summary,records',
        ]);

        try {
            $response = Http::timeout(1000) // Set timeout sesuai kebutuhan
                ->post('https://3089-103-36-14-70.ngrok-free.app/qa', [
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
