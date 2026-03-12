<?php

namespace App\Http\Controllers;

use App\Models\PatientDataset;
use App\Services\NaiveBayesService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(NaiveBayesService $naiveBayesService)
    {
        // Ambil data latih dan uji
        $testingData = PatientDataset::where('is_training', false)->get();
        $totalTesting = $testingData->count();
        $totalTraining = PatientDataset::where('is_training', true)->count();

        // Variabel Confusion Matrix
        $TP = 0; // True Positive (Actual 1, Prediksi 1)
        $TN = 0; // True Negative (Actual 0, Prediksi 0)
        $FP = 0; // False Positive (Actual 0, Prediksi 1)
        $FN = 0; // False Negative (Actual 1, Prediksi 0)

        // Hanya hitung jika data latih & uji tersedia
        if ($totalTesting > 0 && $totalTraining > 0) {
            foreach ($testingData as $data) {
                // Format agar dikenali oleh service
                $patientData = [
                    'age' => $data->age,
                    'gender' => $data->gender,
                    'blood_pressure' => $data->blood_pressure,
                    'cholesterol' => $data->cholesterol,
                    'blood_sugar' => $data->blood_sugar,
                ];

                $prediction = $naiveBayesService->predict($patientData)['prediction_value'];
                $actual = (int) $data->risk_result;

                if ($actual === 1 && $prediction === 1) {
                    $TP++;
                } elseif ($actual === 0 && $prediction === 0) {
                    $TN++;
                } elseif ($actual === 0 && $prediction === 1) {
                    $FP++;
                } elseif ($actual === 1 && $prediction === 0) {
                    $FN++;
                }
            }
        }

        // Kalkulasi Metrics %
        $accuracy = 0;
        $precision = 0;
        $recall = 0;

        if ($totalTesting > 0 && $totalTraining > 0) {
            $accuracy = ($TP + $TN) / $totalTesting * 100;
            $precision = ($TP + $FP) > 0 ? ($TP / ($TP + $FP)) * 100 : 0;
            $recall = ($TP + $FN) > 0 ? ($TP / ($TP + $FN)) * 100 : 0;
        }

        return Inertia::render('Dashboard', [
            'metrics' => [
                'accuracy' => round($accuracy, 2),
                'precision' => round($precision, 2),
                'recall' => round($recall, 2),
            ],
            'confusion_matrix' => [
                'tp' => $TP,
                'tn' => $TN,
                'fp' => $FP,
                'fn' => $FN,
            ],
            'data_counts' => [
                'training' => $totalTraining,
                'testing' => $totalTesting,
            ]
        ]);
    }
}
