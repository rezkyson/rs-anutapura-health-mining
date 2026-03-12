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
        $datasets = PatientDataset::latest()->paginate(20);
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
                
                // Preprocessing: Cek apakah nilai kosong
                if (
                    !isset($data['age']) || $data['age'] === '' ||
                    !isset($data['gender']) || $data['gender'] === '' ||
                    !isset($data['blood_pressure']) || $data['blood_pressure'] === '' ||
                    !isset($data['cholesterol']) || $data['cholesterol'] === '' ||
                    !isset($data['blood_sugar']) || $data['blood_sugar'] === '' ||
                    !isset($data['risk_result']) || $data['risk_result'] === ''
                ) {
                    $nullSkippedCount++;
                    continue;
                }

                $isTraining = isset($data['is_training']) ? filter_var($data['is_training'], FILTER_VALIDATE_BOOLEAN) : true;

                PatientDataset::create([
                    'age' => (int) $data['age'],
                    'gender' => strtoupper(trim($data['gender'])),
                    'blood_pressure' => (int) $data['blood_pressure'],
                    'cholesterol' => (int) $data['cholesterol'],
                    'blood_sugar' => (int) $data['blood_sugar'],
                    'risk_result' => filter_var($data['risk_result'], FILTER_VALIDATE_BOOLEAN),
                    'is_training' => $isTraining
                ]);

                $importedCount++;
            }
            DB::commit();
            
            return redirect()->back()->with('status', "Berhasil import {$importedCount} data. ({$nullSkippedCount} baris diabaikan karena kosong atau tidak lengkap)");
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

        return redirect()->back()->with('status', 'Status data berhasil diubah.');
    }
    
    /**
     * Hapus Data Single
     */
    public function destroy(PatientDataset $dataset)
    {
        $dataset->delete();
        return redirect()->back()->with('status', 'Data berhasil dihapus.');
    }

    /**
     * Kosongkan Dataset
     */
    public function truncate()
    {
        PatientDataset::truncate();
        return redirect()->back()->with('status', 'Seluruh dataset berhasil dihapus.');
    }
}
