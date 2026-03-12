<?php

namespace App\Services;

use App\Models\PatientDataset;
use Illuminate\Support\Facades\DB;

class NaiveBayesService
{
    /**
     * Train data attributes that are continuous
     */
    protected array $continuousFeatures = [
        'age',
        'blood_pressure',
        'cholesterol',
        'blood_sugar'
    ];

    /**
     * Train data attributes that are categorical
     */
    protected array $categoricalFeatures = [
        'gender'
    ];

    /**
     * Menghitung Probabilitas Prior P(C)
     * Mengembalikan probabilitas kelas Beresiko (1) dan Tidak Beresiko (0)
     *
     * @return array
     */
    public function getPriorProbabilities(): array
    {
        $totalData = PatientDataset::where('is_training', true)->count();
        
        if ($totalData === 0) {
            // Default probabilitas seimbang jika belum ada data latih
            return [
                1 => 0.5,
                0 => 0.5
            ];
        }

        $riskCount = PatientDataset::where('is_training', true)
            ->where('risk_result', 1)
            ->count();
            
        $noRiskCount = PatientDataset::where('is_training', true)
            ->where('risk_result', 0)
            ->count();

        return [
            1 => $riskCount / $totalData,
            0 => $noRiskCount / $totalData,
        ];
    }

    /**
     * Menghitung rata-rata (mean) dan variansi (variance) untuk data kontinu,
     * serta probabilitas kategorial (Likelihood P(X|C))
     *
     * @return array
     */
    public function getModelParameters(): array
    {
        $classes = [1, 0];
        $parameters = [];

        foreach ($classes as $class) {
            $classTotal = PatientDataset::where('is_training', true)
                ->where('risk_result', $class)
                ->count();

            // 1. Kategorial Probabilities (Gender)
            foreach ($this->categoricalFeatures as $feature) {
                $categories = PatientDataset::select($feature, DB::raw('count(*) as total'))
                    ->where('is_training', true)
                    ->where('risk_result', $class)
                    ->groupBy($feature)
                    ->pluck('total', $feature)
                    ->toArray();
                
                $parameters[$class]['categorical'][$feature] = [];
                foreach (['L', 'P'] as $catValue) {
                    $count = $categories[$catValue] ?? 0;
                    // Laplace Smoothing sederhana (+1 / +2) untuk mencegah probabilitas 0
                    $parameters[$class]['categorical'][$feature][$catValue] = ($count + 1) / (($classTotal ?: 0) + 2);
                }
            }

            // 2. Data Kontinu (Umur, Tekanan Darah, Kolesterol, Gula Darah)
            foreach ($this->continuousFeatures as $feature) {
                // Rata-rata (Mean)
                $mean = PatientDataset::where('is_training', true)
                    ->where('risk_result', $class)
                    ->avg($feature);

                // Variansi (Populasi)
                $variance = PatientDataset::where('is_training', true)
                    ->where('risk_result', $class)
                    ->selectRaw("VARIANCE({$feature}) as variance")
                    ->value('variance');
                
                // Jika variansi 0, tambahkan nilai kecil Epsilon agar tidak ada error pembagian dengan 0 pada Gaussian
                $variance = max((float) $variance, 1e-6);

                $parameters[$class]['continuous'][$feature] = [
                    'mean' => (float) $mean,
                    'variance' => $variance
                ];
            }
        }

        return $parameters;
    }

    /**
     * Hitung Distribusi Normal Gaussian
     * 
     * @param float $x Nilai observasi yang akan dites
     * @param float $mean Rata-rata latih
     * @param float $variance Variansi latih
     * @return float
     */
    public function calculateGaussianProbability(float $x, float $mean, float $variance): float
    {
        $exponent = exp(-pow($x - $mean, 2) / (2 * $variance));
        return (1 / sqrt(2 * pi() * $variance)) * $exponent;
    }

    /**
     * Eksekusi Prediksi menggunakan data input pasien
     * 
     * @param array $patientData E.g. ['age' => 45, 'gender' => 'L', 'blood_pressure' => 120, 'cholesterol' => 200, 'blood_sugar' => 90]
     * @return array
     */
    public function predict(array $patientData): array
    {
        $priorProbs = $this->getPriorProbabilities();
        $modelParams = $this->getModelParameters();

        $scores = [];

        foreach ([1, 0] as $class) {
            // Gunakan Log-Probability untuk menghindari nilai floating-point yang terlalu kecil (underflow)
            $logProb = log($priorProbs[$class] ?: 1e-9);

            // Tambahkan nilai probabilitas kolom observasi kontinu
            foreach ($this->continuousFeatures as $feature) {
                if (isset($patientData[$feature])) {
                    $mean = $modelParams[$class]['continuous'][$feature]['mean'];
                    $variance = $modelParams[$class]['continuous'][$feature]['variance'];
                    
                    $pLog = $this->calculateGaussianProbability($patientData[$feature], $mean, $variance);
                    $logProb += log($pLog > 0 ? $pLog : 1e-9);
                }
            }

            // Tambahkan nilai probabilitas kolom observasi kategorial
            foreach ($this->categoricalFeatures as $feature) {
                if (isset($patientData[$feature])) {
                    $val = $patientData[$feature];
                    $pCat = $modelParams[$class]['categorical'][$feature][$val] ?? 1e-9;
                    $logProb += log($pCat);
                }
            }

            $scores[$class] = $logProb;
        }

        // Bandingkan Log Probabilitas: skor log tertinggi mendefinisikan label target
        $prediction = $scores[1] > $scores[0] ? 1 : 0;

        return [
            'prediction_label' => $prediction === 1 ? 'Beresiko' : 'Tidak Beresiko',
            'prediction_value' => $prediction,
            'probabilities' => [
                'beresiko_score' => $scores[1],
                'tidak_beresiko_score' => $scores[0],
            ]
        ];
    }
}
