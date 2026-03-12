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
    if (val >= 60) return 'text-indigo-500'; // Indigo for good
    if (val >= 40) return 'text-amber-500'; // Yellow for average
    return 'text-rose-500'; // Red for poor
};

const getMetricBgColor = (val) => {
    if (val >= 80) return 'bg-[#00A651]'; // Green for excellent
    if (val >= 60) return 'bg-indigo-500'; // Indigo for good
    if (val >= 40) return 'bg-amber-500'; // Yellow for average
    return 'bg-rose-500'; // Red for poor
};
</script>

<template>
    <Head title="Enterprise Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-2">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Sistem Monitoring</h2>
                    <p class="text-sm font-medium text-slate-500 mt-0.5">Evaluasi Performa & Metrik Algoritma Klasifikasi Jantung</p>
                </div>
                
                <div class="mt-4 sm:mt-0 flex items-center gap-3">
                    <div class="px-4 py-2 bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Status Model</span>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="w-2 h-2 rounded-full" :class="metrics.accuracy >= 70 ? 'bg-[#00A651] animate-pulse' : 'bg-rose-500 animate-pulse'"></span>
                            <span class="text-sm font-bold text-slate-700 leading-none">Online & Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="space-y-8 pb-10">
            <!-- Alert if testing data is empty -->
            <div v-if="data_counts.testing === 0 || data_counts.training === 0" class="w-full relative overflow-hidden bg-gradient-to-r from-amber-50 to-amber-100/50 border border-amber-200 p-6 rounded-2xl shadow-sm group">
                <!-- Decorative background pattern -->
                <div class="absolute right-0 top-0 w-64 h-full bg-gradient-to-l from-amber-200/50 to-transparent mix-blend-multiply opacity-50 transform rotate-45 scale-150 transition-transform group-hover:rotate-12 duration-700"></div>
                <div class="relative flex z-10">
                    <div class="flex-shrink-0 bg-amber-200/50 p-3 rounded-2xl shadow-inner">
                        <svg class="h-8 w-8 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-xl font-bold text-amber-800">Evaluasi Model Belum Tersedia</h3>
                        
                        <!-- Tampilan Admin -->
                        <div v-if="$page.props.auth.user.id === 1">
                            <div class="mt-2 text-amber-700 max-w-2xl font-medium leading-relaxed">
                                <p>Sistem mendeteksi bahwa rasio data Latih (Training) atau data Uji (Testing) belum dikonfigurasi. Silakan masuk ke Manajemen Dataset untuk mengatur data operasional agar algoritma dapat diukur akurasinya.</p>
                            </div>
                            <div class="mt-5">
                                <Link :href="route('dataset.index')" class="inline-flex items-center px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm rounded-xl transition-colors shadow-md shadow-amber-500/30">
                                    Buka Manajemen Dataset
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 ml-2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                                </Link>
                            </div>
                        </div>

                        <!-- Tampilan Medis -->
                        <div v-else>
                            <div class="mt-2 text-amber-700 max-w-2xl font-medium leading-relaxed">
                                <p>Sistem mendeteksi bahwa rasio data Latih (Training) atau data Uji (Testing) belum dikonfigurasi. Penyesuaian konfigurasi ini hanya bisa dikelola dan dilakukan oleh Administrator IT.</p>
                            </div>
                            <div class="mt-5 flex items-center gap-2 text-amber-700 font-bold bg-amber-200/50 px-4 py-2.5 rounded-xl border border-amber-300/50 inline-flex shadow-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 opacity-70"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                Hubungi Administrator untuk Memuat Dataset
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="w-full space-y-8">
                <!-- Top Overview Metrics (Glassmorphism Cards) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Accuracy -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col justify-between shadow-sm hover:shadow-xl transition-all duration-300 group overflow-hidden relative">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#00A651]/5 rounded-full blur-2xl group-hover:bg-[#00A651]/10 transition-colors"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Akurasi Prediksi</p>
                                <div class="p-2 rounded-lg bg-slate-50 border border-slate-100 text-slate-400 group-hover:text-[#00A651] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                            </div>
                            <div class="mt-4 flex items-baseline gap-2">
                                <span class="text-4xl font-extrabold tracking-tight" :class="getMetricColor(metrics.accuracy)">
                                    {{ metrics.accuracy }}<span class="text-2xl font-bold opacity-60">%</span>
                                </span>
                            </div>
                        </div>
                        <div class="relative z-10 mt-6 w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner">
                            <div class="h-full rounded-full transition-all duration-1000 ease-out relative" :class="getMetricBgColor(metrics.accuracy)" :style="'width: ' + metrics.accuracy + '%'">
                                <div class="absolute inset-0 bg-white/20"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Precision -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col justify-between shadow-sm hover:shadow-xl transition-all duration-300 group overflow-hidden relative">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#00AEEF]/5 rounded-full blur-2xl group-hover:bg-[#00AEEF]/10 transition-colors"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Presisi Positif</p>
                                <div class="p-2 rounded-lg bg-slate-50 border border-slate-100 text-slate-400 group-hover:text-[#00AEEF] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.671zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59" /></svg>
                                </div>
                            </div>
                            <div class="mt-4 flex items-baseline gap-2">
                                <span class="text-4xl font-extrabold tracking-tight" :class="getMetricColor(metrics.precision)">
                                    {{ metrics.precision }}<span class="text-2xl font-bold opacity-60">%</span>
                                </span>
                            </div>
                        </div>
                        <div class="relative z-10 mt-6 w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner">
                            <div class="h-full rounded-full transition-all duration-1000 ease-out relative" :class="getMetricBgColor(metrics.precision)" :style="'width: ' + metrics.precision + '%'">
                                <div class="absolute inset-0 bg-white/20"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Recall -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col justify-between shadow-sm hover:shadow-xl transition-all duration-300 group overflow-hidden relative">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-amber-500/5 rounded-full blur-2xl group-hover:bg-amber-500/10 transition-colors"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start">
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Sensitivitas (Recall)</p>
                                <div class="p-2 rounded-lg bg-slate-50 border border-slate-100 text-slate-400 group-hover:text-amber-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                            </div>
                            <div class="mt-4 flex items-baseline gap-2">
                                <span class="text-4xl font-extrabold tracking-tight" :class="getMetricColor(metrics.recall)">
                                    {{ metrics.recall }}<span class="text-2xl font-bold opacity-60">%</span>
                                </span>
                            </div>
                        </div>
                        <div class="relative z-10 mt-6 w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner">
                            <div class="h-full rounded-full transition-all duration-1000 ease-out relative" :class="getMetricBgColor(metrics.recall)" :style="'width: ' + metrics.recall + '%'">
                                <div class="absolute inset-0 bg-white/20"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Dataset Health / Split -->
                    <div class="relative rounded-2xl overflow-hidden shadow-sm group hover:shadow-xl transition-all duration-300">
                        <!-- Tech Background Map/Grid -->
                        <div class="absolute inset-0 bg-[#0A192F]"></div>
                        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCI+PHRleHQgeD0iMCIgeT0iMTIiIGZpbGw9InJnYmEoMjU1LCAyNTUsIDI1NSwgMC4wNykiIGZvbnQtZmFtaWx5PSJzYW5zLXNlcmlmIiBmb250LXNpemU9IjIwcHgiPi48L3RleHQ+PC9zdmc+')] bg-[length:20px_20px]"></div>
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-[#00AEEF] to-transparent mix-blend-screen opacity-50 blur-xl"></div>
                        
                        <div class="relative z-10 p-6 flex flex-col justify-between h-full">
                            <div class="flex justify-between items-start">
                                <p class="text-[11px] font-bold text-slate-300 uppercase tracking-widest">Infrastruktur Data</p>
                                <svg class="w-5 h-5 text-[#00AEEF]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                            </div>
                            
                            <div class="mt-4 flex flex-col gap-3">
                                <div class="flex items-center justify-between border-b border-slate-700/50 pb-2">
                                    <span class="text-sm text-slate-400 font-medium">Training Data</span>
                                    <span class="text-lg font-bold text-white">{{ data_counts.training }} <span class="text-xs text-slate-500 font-normal">Records</span></span>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-sm text-slate-400 font-medium">Testing Data</span>
                                    <span class="text-lg font-bold text-[#00AEEF]">{{ data_counts.testing }} <span class="text-xs text-slate-500 font-normal">Records</span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Confusion Matrix Enterprise Visualization -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm lg:col-span-2 relative">
                    <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-800">Confusion Matrix Array</h3>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-1">Cross-Validation Result on {{ data_counts.testing }} Testing Samples</p>
                        </div>
                    </div>
                    
                    <div class="p-8 lg:p-10 flex flex-col lg:flex-row gap-12 lg:gap-20 items-center justify-center relative bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCI+PHRleHQgeD0iMCIgeT0iMTIiIGZpbGw9InJnYmEoMTQ4LCAxNjMsIDE4NCLCAwLjA0KSIgZm9udC1mYW1pbHk9InNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjBweCI+LjwvdGV4dD48L3N2Zz4=')]">

                        <!-- Main Matrix Heatmap Grid -->
                        <div class="relative flex-shrink-0">
                            <!-- Axis Labels -->
                            <div class="absolute -left-12 top-1/2 -translate-y-1/2 -rotate-90 flex items-center gap-2">
                                <span class="text-[10px] uppercase font-black tracking-[0.2em] text-slate-400">Data Aktual (Kenyataan)</span>
                            </div>
                            
                            <div class="flex flex-col">
                                <!-- Top X Axis (Predicted) -->
                                <div class="flex mb-4">
                                    <div class="w-10"></div> <!-- Spacer for Y axis labels -->
                                    <div class="w-40 text-center">
                                        <span class="text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Prediksi Positif</span>
                                        <div class="text-[10px] font-bold text-rose-500 bg-rose-50 px-2 py-0.5 rounded-full inline-block mt-1">Beresiko (1)</div>
                                    </div>
                                    <div class="w-40 text-center">
                                        <span class="text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Prediksi Negatif</span>
                                        <div class="text-[10px] font-bold text-[#00A651] bg-[#00A651]/10 px-2 py-0.5 rounded-full inline-block mt-1">Aman (0)</div>
                                    </div>
                                </div>

                                <!-- Box Grid System -->
                                <div class="relative bg-slate-200 gap-[1px] grid grid-cols-[auto_160px_160px] rounded-2xl overflow-hidden shadow-inner border border-slate-200 p-[1px]">
                                    
                                    <!-- ROW 1 (Actual Positive) -->
                                    <div class="bg-slate-50 w-12 flex flex-col items-center justify-center p-2 text-center rounded-l-xl">
                                        <span class="text-[10px] font-bold text-rose-500 -rotate-90 whitespace-nowrap">Beresiko (1)</span>
                                    </div>
                                    
                                    <!-- True Positive (TP) -->
                                    <div class="h-40 bg-white relative group overflow-hidden transition-all hover:bg-emerald-50/30 flex flex-col items-center justify-center">
                                        <!-- Heat background based on value -->
                                        <div class="absolute bottom-0 left-0 right-0 bg-[#00A651]/10 z-0 transition-all duration-1000" :style="{ height: Math.min((confusion_matrix.tp / (data_counts.testing || 1)) * 200, 100) + '%' }"></div>
                                        <div class="relative z-10 flex flex-col items-center">
                                            <span class="text-4xl font-extrabold text-slate-800 drop-shadow-sm">{{ confusion_matrix.tp }}</span>
                                            <span class="text-[10px] uppercase tracking-wider font-bold text-[#00A651] mt-2 bg-[#00A651]/10 px-2 py-1 rounded">True Positive</span>
                                        </div>
                                    </div>

                                    <!-- False Negative (FN) - Danger -->
                                    <div class="h-40 bg-white relative group overflow-hidden transition-all hover:bg-rose-50/30 flex flex-col items-center justify-center rounded-tr-xl">
                                        <div class="absolute bottom-0 left-0 right-0 bg-rose-500/10 z-0 transition-all duration-1000" :style="{ height: Math.min((confusion_matrix.fn / (data_counts.testing || 1)) * 200, 100) + '%' }"></div>
                                        <div class="relative z-10 flex flex-col items-center">
                                            <span class="text-4xl font-extrabold text-slate-800 drop-shadow-sm">{{ confusion_matrix.fn }}</span>
                                            <span class="text-[10px] uppercase tracking-wider font-bold text-rose-500 mt-2 bg-rose-50 px-2 py-1 rounded">False Negative</span>
                                        </div>
                                    </div>

                                    <!-- ROW 2 (Actual Negative) -->
                                    <div class="bg-slate-50 w-12 flex flex-col items-center justify-center p-2 text-center rounded-bl-xl border-t border-slate-200">
                                        <span class="text-[10px] font-bold text-[#00A651] -rotate-90 whitespace-nowrap">Aman (0)</span>
                                    </div>
                                    
                                    <!-- False Positive (FP) - Warning -->
                                    <div class="h-40 bg-white relative group overflow-hidden transition-all hover:bg-amber-50/30 flex flex-col items-center justify-center">
                                        <div class="absolute bottom-0 left-0 right-0 bg-amber-500/10 z-0 transition-all duration-1000" :style="{ height: Math.min((confusion_matrix.fp / (data_counts.testing || 1)) * 200, 100) + '%' }"></div>
                                        <div class="relative z-10 flex flex-col items-center">
                                            <span class="text-4xl font-extrabold text-slate-800 drop-shadow-sm">{{ confusion_matrix.fp }}</span>
                                            <span class="text-[10px] uppercase tracking-wider font-bold text-amber-500 mt-2 bg-amber-50 px-2 py-1 rounded">False Positive</span>
                                        </div>
                                    </div>

                                    <!-- True Negative (TN) -->
                                    <div class="h-40 bg-white relative group overflow-hidden transition-all hover:bg-[#00AEEF]/5 flex flex-col items-center justify-center rounded-br-xl">
                                        <!-- Heat background based on value -->
                                        <div class="absolute bottom-0 left-0 right-0 bg-[#00AEEF]/10 z-0 transition-all duration-1000" :style="{ height: Math.min((confusion_matrix.tn / (data_counts.testing || 1)) * 200, 100) + '%' }"></div>
                                        <div class="relative z-10 flex flex-col items-center">
                                            <span class="text-4xl font-extrabold text-slate-800 drop-shadow-sm">{{ confusion_matrix.tn }}</span>
                                            <span class="text-[10px] uppercase tracking-wider font-bold text-[#00AEEF] mt-2 bg-blue-50 px-2 py-1 rounded">True Negative</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Professional Legend Card -->
                        <div class="w-full max-w-sm bg-white border border-slate-200 rounded-2xl shadow-lg shadow-slate-200/50 p-6 relative overflow-hidden">
                            <!-- Inner decoration -->
                            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-slate-100 to-transparent rounded-bl-full pointer-events-none"></div>
                            
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <svg class="h-4 w-4 text-[#00AEEF]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Analisis Matriks Medis
                            </h4>
                            
                            <div class="space-y-4 relative z-10">
                                <div class="flex gap-4 items-start group">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                                        <span class="text-[#00A651] font-bold text-[10px]">TP</span>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-bold text-slate-800">Sempurna (Akurat)</h5>
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Sistem mendeteksi pasien BERESIKO, dan kenyataannya memang benar.</p>
                                    </div>
                                </div>
                                <div class="flex gap-4 items-start group">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center shrink-0 mt-0.5">
                                        <span class="text-[#00AEEF] font-bold text-[10px]">TN</span>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-bold text-slate-800">Akurat (Aman)</h5>
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Sistem mendeteksi pasien AMAN, dan kenyataannya memang benar (Sehat).</p>
                                    </div>
                                </div>
                                <div class="h-px w-full bg-slate-100 my-2"></div>
                                <div class="flex gap-4 items-start group">
                                    <div class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-100 flex items-center justify-center shrink-0 mt-0.5">
                                        <span class="text-rose-500 font-bold text-[10px] animate-pulse">FN</span>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-bold text-rose-600">Error Fatal (Missed)</h5>
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Prediksi AMAN, padahal aslinya SAKIT. (Nilai ini harus ditekan seminimal mungkin pada instansi medis!).</p>
                                    </div>
                                </div>
                                <div class="flex gap-4 items-start group">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center shrink-0 mt-0.5">
                                        <span class="text-amber-500 font-bold text-[10px]">FP</span>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-bold text-amber-600">Alarm Palsu</h5>
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Prediksi SAKIT, padahal aslinya AMAN.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
