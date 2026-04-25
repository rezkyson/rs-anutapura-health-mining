<?php

namespace App\Http\Controllers;

use App\Models\PatientDataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PatientDatasetController extends Controller
{
    /**
     * Tampilkan daftar dataset.
     */
    public function index(): Response
    {
        $datasets = PatientDataset::orderBy('id', 'asc')->paginate(10);
        $totalTraining = PatientDataset::where('is_training', true)->count();
        $totalTesting = PatientDataset::where('is_training', false)->count();

        return Inertia::render('Admin/Dataset/Index', [
            'datasets' => $datasets,
            'stats' => [
                'training' => $totalTraining,
                'testing' => $totalTesting,
                'total' => $totalTraining + $totalTesting
            ]
        ]);
    }

    /**
     * Import dataset dari file CSV sesuai PRD.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:4096'
        ]);

        $file = $request->file('file');
        
        $csvData = file_get_contents($file);
        // Memisahkan baris dengan aman (termasuk CRLF atau LF)
        $lines = preg_split('/\r\n|\r|\n/', trim($csvData));
        $rows = array_map('str_getcsv', $lines);
        $header = array_shift($rows);

        // Bersihkan spasi dari header
        $header = array_map('trim', $header);
        
        $importedCount = 0;
        $nullSkippedCount = 0;
        
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                // Lewati jika baris kosong atau jumlah kolom tidak sama dengan header
                if (empty(array_filter($row)) || count($row) !== count($header)) continue;
                
                $data = array_combine($header, $row);
                
                // Menerima Format English atau Indonesia
                $age = $data['age'] ?? $data['usia'] ?? null;
                $gender = $data['gender'] ?? $data['jenis_kelamin'] ?? null;
                $bp = $data['blood_pressure'] ?? $data['tekanan_darah'] ?? null;
                $chol = $data['cholesterol'] ?? $data['kolesterol'] ?? null;
                $sugar = $data['blood_sugar'] ?? $data['gula_darah'] ?? null;
                $risk = $data['risk_result'] ?? $data['risiko'] ?? null;

                // Preprocessing: Cek apakah nilai kosong
                if (
                    $age === null || $age === '' ||
                    $gender === null || $gender === '' ||
                    $bp === null || $bp === '' ||
                    $chol === null || $chol === '' ||
                    $sugar === null || $sugar === '' ||
                    $risk === null || $risk === ''
                ) {
                    $nullSkippedCount++;
                    continue;
                }

                $isTraining = isset($data['is_training']) ? filter_var($data['is_training'], FILTER_VALIDATE_BOOLEAN) : true;

                // Konversi kode gender: 1=Laki-laki, 0=Perempuan.
                $genderVal = trim($gender);
                if ($genderVal === '1' || strtoupper($genderVal) === 'L' || strtoupper($genderVal) === 'LAKI-LAKI') {
                    $genderVal = 'L';
                } else {
                    $genderVal = 'P';
                }

                PatientDataset::create([
                    'age' => (int) $age,
                    'gender' => $genderVal,
                    'blood_pressure' => (int) $bp,
                    'cholesterol' => (int) $chol,
                    'blood_sugar' => (int) $sugar,
                    'risk_result' => filter_var($risk, FILTER_VALIDATE_BOOLEAN),
                    'is_training' => $isTraining
                ]);

                $importedCount++;
            }
            DB::commit();
            
            return redirect()->back()->with('success', "Berhasil import {$importedCount} data. ({$nullSkippedCount} baris diabaikan karena kosong atau tidak lengkap)");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['file' => 'Terjadi kesalahan impor. Format kolom CSV harus: age, gender, blood_pressure, cholesterol, blood_sugar, risk_result']);
        }
    }
    
    /**
     * Ubah status Data Latih / Data Uji
     */
    public function setSplit(Request $request, PatientDataset $dataset)
    {
        $request->validate([
            'is_training' => 'required|boolean'
        ]);

        $dataset->update([
            'is_training' => $request->is_training
        ]);

        return redirect()->back()->with('success', 'Status data berhasil diubah.');
    }
    
    /**
     * Hapus Data Single
     */
    public function destroy(PatientDataset $dataset)
    {
        $dataset->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Kosongkan Dataset
     */
    public function truncate()
    {
        PatientDataset::truncate();
        return redirect()->back()->with('success', 'Seluruh dataset berhasil dihapus.');
    }

    /**
     * Bagi dataset otomatis: 80% Training, 20% Testing
     */
    public function autoSplit(Request $request)
    {
        $ratio = $request->input('ratio', 80); // default 80% training
        $ratio = max(50, min(90, (int) $ratio)); // batasi antara 50-90%

        $allIds = PatientDataset::pluck('id')->toArray();
        $total = count($allIds);

        if ($total === 0) {
            return redirect()->back()->withErrors(['split' => 'Tidak ada data untuk dibagi.']);
        }

        // Acak urutan ID
        shuffle($allIds);

        $trainingCount = (int) round($total * $ratio / 100);
        $trainingIds = array_slice($allIds, 0, $trainingCount);
        $testingIds = array_slice($allIds, $trainingCount);

        // Update seluruh data sekaligus
        PatientDataset::whereIn('id', $trainingIds)->update(['is_training' => true]);
        PatientDataset::whereIn('id', $testingIds)->update(['is_training' => false]);

        $testingCount = count($testingIds);

        return redirect()->back()->with('success', "Dataset berhasil dibagi: {$trainingCount} data latih ({$ratio}%) dan {$testingCount} data uji (" . (100 - $ratio) . "%).");
    }
}
