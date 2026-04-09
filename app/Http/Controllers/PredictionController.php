<?php

namespace App\Http\Controllers;

use App\Services\NaiveBayesService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PredictionController extends Controller
{
    protected NaiveBayesService $naiveBayesService;

    public function __construct(NaiveBayesService $naiveBayesService)
    {
        $this->naiveBayesService = $naiveBayesService;
    }

    /**
     * Tampilkan form prediksi untuk tenaga medis.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Medical/Prediction/Index', [
            'result' => session('result'),
            'probabilities' => session('probabilities'),
            'log_probabilities' => session('log_probabilities'),
        ]);
    }

    /**
     * Jalankan proses prediksi.
     */
    public function predict(Request $request)
    {
        $validatedData = $request->validate([
            'age' => 'required|integer|min:1|max:150',
            'gender' => 'required|in:L,P',
            'blood_pressure' => 'required|integer|min:30|max:250',
            'cholesterol' => 'required|integer|min:50|max:600',
            'blood_sugar' => 'required|in:0,1',
        ]);

        // Proses prediksi via Naive Bayes Service
        $result = $this->naiveBayesService->predict($validatedData);

        $log1 = $result['probabilities']['beresiko_score'];
        $log0 = $result['probabilities']['tidak_beresiko_score'];

        // Konversi log-probabilitas kembali menjadi probabilitas persentase (Normalisasi)
        $maxLog = max($log1, $log0);
        $p1 = exp($log1 - $maxLog);
        $p0 = exp($log0 - $maxLog);
        $sum = $p1 + $p0;

        $prob1 = $p1 / $sum;
        $prob0 = $p0 / $sum;

        // Kembalikan ke halaman form dengan data hasil via Flash Session
        return redirect()->back()->with([
            'result' => [
                'prediction' => $result['prediction_value'],
            ],
            'probabilities' => [
                1 => $prob1,
                0 => $prob0,
            ],
            'log_probabilities' => [
                1 => $log1,
                0 => $log0,
            ]
        ]);
    }
}
