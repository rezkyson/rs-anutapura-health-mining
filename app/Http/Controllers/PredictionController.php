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
    public function index(): Response
    {
        return Inertia::render('Medical/Prediction/Index');
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
            'blood_sugar' => 'required|integer|min:30|max:500',
        ]);

        // Proses prediksi via Naive Bayes Service
        $result = $this->naiveBayesService->predict($validatedData);

        // Kembalikan ke halaman form dengan data hasil (sebagai flash session)
        return redirect()->back()->with('prediction_result', $result);
    }
}
