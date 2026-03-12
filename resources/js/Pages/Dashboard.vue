<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    metrics: Object,
    confusion_matrix: Object,
    data_counts: Object,
});

// Helper to determine color based on percentage
const getMetricColor = (val) => {
    if (val >= 80) return 'text-[#00A651]'; // Green for excellent
    if (val >= 60) return 'text-[#00AEEF]'; // Blue for good
    if (val >= 40) return 'text-yellow-500'; // Yellow for average
    return 'text-red-500'; // Red for poor
};
</script>

<template>
    <Head title="Dashboard Evaluasi" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold leading-tight text-gray-800 tracking-tight">
                <span class="text-[#00AEEF]">Sistem Evaluasi</span> <span class="text-gray-400 font-normal mx-2">/</span> Performa Naive Bayes
            </h2>
        </template>

        <div class="py-12 bg-gray-50/50 min-h-screen">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8 flex flex-col items-center justify-center">

                <!-- Alert if testing data is empty -->
                <div v-if="data_counts.testing === 0 || data_counts.training === 0" class="w-full bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-xl shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-yellow-800">Evaluasi Model Belum Tersedia</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Sistem mendeteksi bahwa data latih (training) atau data uji (testing) belum diatur di menu Dataset Admin. Silakan upload dataset dan set minimal sebagian data sebagai Data Uji untuk melihat hasil pengukuran performa model.</p>
                            </div>
                            <div class="mt-4">
                                <Link :href="route('dataset.index')" class="text-sm font-medium text-yellow-800 hover:text-yellow-600 underline">
                                    Buka Manajemen Dataset &rarr;
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="w-full space-y-8">
                    <!-- Top Overview Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        
                        <!-- Accuracy -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tingkat Akurasi</p>
                                <div class="mt-2 flex items-baseline gap-2">
                                    <span class="text-4xl font-black tracking-tight" :class="getMetricColor(metrics.accuracy)">
                                        {{ metrics.accuracy }}%
                                    </span>
                                </div>
                            </div>
                            <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full" :class="'bg-current ' + getMetricColor(metrics.accuracy)" :style="'width: ' + metrics.accuracy + '%'"></div>
                            </div>
                        </div>

                        <!-- Precision -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Presisi (Precision)</p>
                                <div class="mt-2 flex items-baseline gap-2">
                                    <span class="text-4xl font-black tracking-tight" :class="getMetricColor(metrics.precision)">
                                        {{ metrics.precision }}%
                                    </span>
                                </div>
                            </div>
                            <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full" :class="'bg-current ' + getMetricColor(metrics.precision)" :style="'width: ' + metrics.precision + '%'"></div>
                            </div>
                        </div>

                        <!-- Recall -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Recall (Sensitivitas)</p>
                                <div class="mt-2 flex items-baseline gap-2">
                                    <span class="text-4xl font-black tracking-tight" :class="getMetricColor(metrics.recall)">
                                        {{ metrics.recall }}%
                                    </span>
                                </div>
                            </div>
                            <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full" :class="'bg-current ' + getMetricColor(metrics.recall)" :style="'width: ' + metrics.recall + '%'"></div>
                            </div>
                        </div>

                        <!-- Data Split -->
                        <div class="bg-gradient-to-br from-[#00AEEF] to-[#0088cc] overflow-hidden shadow-sm sm:rounded-2xl border border-transparent p-6 flex flex-col justify-between text-white">
                            <div>
                                <p class="text-sm font-medium text-blue-100 uppercase tracking-wider">Proporsi Dataset</p>
                                <div class="mt-4 flex flex-col gap-2">
                                    <div class="flex justify-between items-center bg-white/10 px-3 py-2 rounded-lg">
                                        <span class="text-sm">Data Latih (Train)</span>
                                        <span class="font-bold">{{ data_counts.training }} baris</span>
                                    </div>
                                    <div class="flex justify-between items-center bg-white/10 px-3 py-2 rounded-lg">
                                        <span class="text-sm">Data Uji (Test)</span>
                                        <span class="font-bold">{{ data_counts.testing }} baris</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Confusion Matrix Section -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                        <div class="border-b border-gray-100 bg-gray-50/50 px-8 py-5">
                            <h3 class="text-lg font-semibold text-gray-800">Confusion Matrix Analysis</h3>
                            <p class="text-sm text-gray-500 mt-1">Perbandingan antara Nilai Aktual vs Hasil Prediksi Sistem pada {{ data_counts.testing }} Data Uji</p>
                        </div>
                        <div class="p-8">
                            
                            <div class="flex flex-col md:flex-row gap-12 items-center justify-center">
                                <!-- Matrix Grid -->
                                <div class="relative">
                                    <!-- Labels Y (Actual) -->
                                    <div class="absolute -left-16 top-1/2 -translate-y-1/2 -rotate-90 text-sm font-bold text-gray-500 tracking-widest uppercase">
                                        Data Aktual
                                    </div>
                                    
                                    <div class="flex flex-col">
                                        <!-- Labels X (Predicted) -->
                                        <div class="flex mb-4">
                                            <div class="w-16"></div> <!-- Spacer -->
                                            <div class="w-32 text-center text-sm font-bold text-gray-500 tracking-widest uppercase">Prediksi Positif</div>
                                            <div class="w-32 text-center text-sm font-bold text-gray-500 tracking-widest uppercase">Prediksi Negatif</div>
                                        </div>

                                        <!-- Rows -->
                                        <div class="flex items-center mb-4 gap-4">
                                            <div class="w-16 text-right text-sm font-bold text-gray-500 uppercase pr-2 leading-tight">Positif (Beresiko)</div>
                                            <!-- TP -->
                                            <div class="w-32 h-32 bg-green-50 border-2 border-[#00A651] rounded-xl flex flex-col items-center justify-center shadow-inner hover:bg-green-100 transition-colors">
                                                <span class="text-3xl font-black text-[#00A651]">{{ confusion_matrix.tp }}</span>
                                                <span class="text-xs font-semibold text-green-700 mt-1 text-center">True Positive<br/>(Benar Positif)</span>
                                            </div>
                                            <!-- FN -->
                                            <div class="w-32 h-32 bg-red-50 border border-red-200 rounded-xl flex flex-col items-center justify-center shadow-inner hover:bg-red-100 transition-colors">
                                                <span class="text-3xl font-black text-red-500">{{ confusion_matrix.fn }}</span>
                                                <span class="text-xs font-semibold text-red-700 mt-1 text-center">False Negative<br/>(Salah Negatif)</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-4">
                                            <div class="w-16 text-right text-sm font-bold text-gray-500 uppercase pr-2 leading-tight">Negatif (Aman)</div>
                                            <!-- FP -->
                                            <div class="w-32 h-32 bg-yellow-50 border border-yellow-200 rounded-xl flex flex-col items-center justify-center shadow-inner hover:bg-yellow-100 transition-colors">
                                                <span class="text-3xl font-black text-yellow-600">{{ confusion_matrix.fp }}</span>
                                                <span class="text-xs font-semibold text-yellow-700 mt-1 text-center">False Positive<br/>(Salah Positif)</span>
                                            </div>
                                            <!-- TN -->
                                            <div class="w-32 h-32 bg-green-50 border-2 border-[#00A859] rounded-xl flex flex-col items-center justify-center shadow-inner hover:bg-green-100 transition-colors">
                                                <span class="text-3xl font-black text-[#00A651]">{{ confusion_matrix.tn }}</span>
                                                <span class="text-xs font-semibold text-green-700 mt-1 text-center">True Negative<br/>(Benar Negatif)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Explanation Box -->
                                <div class="max-w-xs bg-gray-50 rounded-xl p-6 border border-gray-100">
                                    <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                                        <svg class="h-5 w-5 text-[#00AEEF]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                        Glosarium Matriks
                                    </h4>
                                    <ul class="space-y-3 text-sm text-gray-600">
                                        <li><strong class="text-[#00A651]">True Positive:</strong> Di prediksi Beresiko, aslinya Beresiko (Akurat).</li>
                                        <li><strong class="text-[#00A651]">True Negative:</strong> Di prediksi Aman, aslinya Aman (Akurat).</li>
                                        <li><strong class="text-red-500">False Negative:</strong> Di prediksi Aman, tapi aslinya sakit (Bahaya / Error).</li>
                                        <li><strong class="text-yellow-600">False Positive:</strong> Di prediksi Sakit, tapi aslinya aman (Alarm Palsu).</li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
