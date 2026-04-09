<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    result: Object,
    probabilities: Object,
    log_probabilities: Object,
});

const isCalculating = ref(false);

const form = useForm({
    age: '',
    gender: '', // 1 or 2
    blood_sugar: '', // Exact number, e.g. 110 mg/dl
    blood_pressure: '', // Exact number (systolic), e.g. 120
    cholesterol: '', // Exact number, e.g. 190 mg/dl
});

const submit = () => {
    isCalculating.value = true;
    form.post(route('prediction.predict'), {
        preserveScroll: true,
        onFinish: () => {
            // Fake delay for AI effect
            setTimeout(() => {
                isCalculating.value = false;
            }, 800);
        }
    });
};

const resetPrediction = () => {
    isCalculating.value = false;
    form.reset();
    form.clearErrors();
    router.visit(route('prediction.index'));
};
</script>

<template>
    <Head title="Modul Prediksi Medis" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-2">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight dark:text-slate-100">Sistem Deteksi Dini</h2>
                    <p class="text-sm font-medium text-slate-500 mt-0.5">Penilaian Risiko Kardiovaskular Berbasis Naive Bayes</p>
                </div>
            </div>
        </template>

        <div class="space-y-8 pb-10">
            <!-- Medical Disclaimer Banner -->
            <div class="bg-gradient-to-r from-blue-50 dark:from-blue-900/30 to-indigo-50/50 dark:to-indigo-900/20 border border-blue-100 dark:border-blue-800/50 rounded-2xl p-5 flex items-start sm:items-center gap-4 shadow-sm relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/10 blur-xl mix-blend-multiply rounded-full pointer-events-none"></div>
                <div class="bg-white dark:bg-slate-800 p-3 rounded-full shadow-sm text-blue-600 dark:text-blue-400 shrink-0 border border-blue-100/50 dark:border-blue-800/50 relative z-10">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div class="relative z-10">
                    <h4 class="font-black text-blue-900 dark:text-blue-300 text-sm tracking-wide">PERHATIAN KLINIS</h4>
                    <p class="text-[13px] text-blue-800/80 dark:text-blue-200/70 leading-relaxed font-medium mt-1">
                        Modul ini dirancang secara eksklusif sebagai <strong>Sistem Pendukung Keputusan (DSS)</strong> untuk membantu spesialis medis. Prediksi berbasis probabilitas ini <strong>TIDAK DAPAT menggantikan penilaian medis, diagnosis formal, atau saran dari dokter bersertifikat.</strong>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                
                <!-- Left Column: Patient Data Entry (EMR Style) -->
                <div class="xl:col-span-7">
                    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden relative group transition-shadow hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-slate-900/50">
                        
                        <!-- Header Bar -->
                        <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-700/60 bg-slate-50/30 dark:bg-slate-800/80 flex items-center justify-between">
                            <h3 class="text-lg font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 border border-indigo-100 dark:border-indigo-800 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                Profil Input Pasien
                            </h3>
                            <button @click="form.reset()" type="button" class="text-xs font-bold text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors uppercase tracking-widest border border-slate-200 dark:border-slate-600 rounded-lg px-3 py-1.5 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none">Reset Parameter</button>
                        </div>

                        <!-- Form Content -->
                        <div class="p-8">
                            <form @submit.prevent="submit" class="space-y-8">
                                
                                <!-- Core Demographics -->
                                <div>
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div> Demografi Utama
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-1.5 focus-within:text-[#00AEEF] transition-colors">
                                            <label for="age" class="block font-bold text-slate-700 dark:text-slate-300 text-sm">Usia (Tahun)</label>
                                            <input type="number" id="age" v-model="form.age" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-[#00AEEF] focus:border-[#00AEEF] focus:bg-white dark:focus:bg-slate-800 text-slate-800 dark:text-slate-100 transition-all font-bold placeholder-slate-300 dark:placeholder-slate-600 shadow-sm" placeholder="Contoh: 45" required min="1" max="120">
                                        </div>
                                        
                                        <div class="space-y-1.5">
                                            <label class="block font-bold text-slate-700 dark:text-slate-300 text-sm">Jenis Kelamin</label>
                                            <div class="grid grid-cols-2 gap-3 mt-1">
                                                <label class="relative flex cursor-pointer rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-3 shadow-sm hover:bg-white dark:hover:bg-slate-800 hover:border-[#00AEEF]/50 transition-all has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/30 has-[:checked]:border-[#00AEEF] has-[:checked]:ring-1 has-[:checked]:ring-[#00AEEF] group">
                                                    <input type="radio" v-model="form.gender" value="L" class="sr-only" required>
                                                    <span class="flex-1 flex text-sm font-bold items-center justify-center gap-2 text-slate-600 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200 transition-colors">
                                                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                                        Laki-laki (1)
                                                    </span>
                                                </label>
                                                <label class="relative flex cursor-pointer rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-3 shadow-sm hover:bg-white dark:hover:bg-slate-800 hover:border-pink-400/50 transition-all has-[:checked]:bg-pink-50 dark:has-[:checked]:bg-pink-900/30 has-[:checked]:border-pink-500 has-[:checked]:ring-1 has-[:checked]:ring-pink-500 group">
                                                    <input type="radio" v-model="form.gender" value="P" class="sr-only" required>
                                                    <span class="flex-1 flex text-sm font-bold items-center justify-center gap-2 text-slate-600 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200 transition-colors">
                                                        <svg class="w-4 h-4 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4h.252c1.042-.016 2.071.21 3.003.655l.08.038z" /></svg>
                                                        Perempuan (0)
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="border-slate-100 dark:border-slate-700">

                                <!-- Clinical Markers -->
                                <div>
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-rose-400"></div> Indikator Biometrik
                                    </h4>
                                    
                                    <div class="space-y-5">
                                        <!-- Blood Sugar -->
                                        <div>
                                            <label class="font-bold text-slate-700 dark:text-slate-300 text-sm mb-2 block">Gula Darah Puasa</label>
                                            <div class="grid grid-cols-2 gap-3 mt-1">
                                                <label class="relative flex cursor-pointer rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-3 shadow-sm hover:bg-white dark:hover:bg-slate-800 hover:border-[#00A651]/50 transition-all has-[:checked]:bg-emerald-50 dark:has-[:checked]:bg-emerald-900/30 has-[:checked]:border-[#00A651] has-[:checked]:ring-1 has-[:checked]:ring-[#00A651] group">
                                                    <input type="radio" v-model="form.blood_sugar" value="0" class="sr-only" required>
                                                    <span class="flex-1 flex text-sm font-bold items-center justify-center gap-2 text-slate-600 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200 transition-colors">
                                                        <svg class="w-4 h-4 text-[#00A651]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        Normal (0)
                                                    </span>
                                                </label>
                                                <label class="relative flex cursor-pointer rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-3 shadow-sm hover:bg-white dark:hover:bg-slate-800 hover:border-rose-400/50 transition-all has-[:checked]:bg-rose-50 dark:has-[:checked]:bg-rose-900/30 has-[:checked]:border-rose-500 has-[:checked]:ring-1 has-[:checked]:ring-rose-500 group">
                                                    <input type="radio" v-model="form.blood_sugar" value="1" class="sr-only" required>
                                                    <span class="flex-1 flex text-sm font-bold items-center justify-center gap-2 text-slate-600 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200 transition-colors">
                                                        <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                        Tinggi (1)
                                                    </span>
                                                </label>
                                            </div>
                                            <p class="text-[11px] text-slate-400 mt-1.5 font-medium">*Berdasarkan hasil lab pasien (≤120 mg/dl = Normal, >120 mg/dl = Tinggi)</p>
                                        </div>

                                        <!-- Blood Pressure -->
                                        <div>
                                            <label for="blood_pressure" class="font-bold text-slate-700 dark:text-slate-300 text-sm mb-2 block flex items-center justify-between">
                                                Tekanan Darah (Sistolik)
                                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded uppercase">mmHg</span>
                                            </label>
                                            <input type="number" id="blood_pressure" v-model="form.blood_pressure" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-[#00AEEF] focus:border-[#00AEEF] focus:bg-white dark:focus:bg-slate-800 text-slate-800 dark:text-slate-100 transition-all font-bold placeholder-slate-300 dark:placeholder-slate-600 shadow-sm" placeholder="Contoh: 120" required min="50" max="250">
                                            <p class="text-[11px] text-slate-400 mt-1 font-medium">*Masukkan tekanan darah sistolik dominan (atas)</p>
                                        </div>

                                        <!-- Cholesterol -->
                                        <div>
                                            <label for="cholesterol" class="font-bold text-slate-700 dark:text-slate-300 text-sm mb-2 block flex items-center justify-between">
                                                Kadar Kolesterol
                                                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded uppercase">mg/dl</span>
                                            </label>
                                            <input type="number" id="cholesterol" v-model="form.cholesterol" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-[#00AEEF] focus:border-[#00AEEF] focus:bg-white dark:focus:bg-slate-800 text-slate-800 dark:text-slate-100 transition-all font-bold placeholder-slate-300 dark:placeholder-slate-600 shadow-sm" placeholder="Contoh: 210" required min="50" max="600">
                                            <p class="text-[11px] text-slate-400 mt-1 font-medium">*Angka spesifik kolesterol total pasien</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" :disabled="form.processing || isCalculating" class="group relative w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-xl shadow-[#00AEEF]/20 text-base font-extrabold text-white bg-gradient-to-r from-[#00AEEF] to-[#0088cc] hover:from-[#009ce6] hover:to-[#007ab8] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00AEEF] overflow-hidden transition-all transform hover:-translate-y-0.5">
                                        <!-- Lighting effect inside button -->
                                        <span class="absolute right-0 w-8 h-[300%] bg-white/30 rotate-12 -translate-y-[50%] translate-x-12 opacity-0 group-hover:animate-[shine_1s_ease-in-out]"></span>
                                        
                                        <svg v-if="isCalculating" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span v-if="!isCalculating">Eksekusi Analisis Naive Bayes</span>
                                        <span v-else>Memproses Kalkulasi Probabilitas...</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Results Station (Sticky Panel) -->
                <div class="xl:col-span-5 h-full relative">
                    <div class="sticky top-6">
                        
                        <!-- AI Calculation Animation State -->
                        <div v-if="isCalculating" class="bg-[#0A192F] rounded-3xl p-10 flex flex-col items-center justify-center min-h-[550px] shadow-2xl overflow-hidden relative border border-slate-700">
                            <!-- Matrix style animated bg -->
                            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCI+PHRleHQgeD0iMCIgeT0iMTIiIGZpbGw9InJnYmEoMjU1LCAyNTUsIDI1NSwgMC4wNCkiIGZvbnQtZmFtaWx5PSJzYW5zLXNlcmlmIiBmb250LXNpemU9IjIwcHgiPi48L3RleHQ+PC9zdmc+')] bg-[length:30px_30px] opacity-10"></div>
                            
                            <div class="relative z-10 flex flex-col items-center gap-6">
                                <!-- Scanning Radar Animation -->
                                <div class="relative w-32 h-32 flex items-center justify-center">
                                    <div class="absolute w-full h-full border-2 border-[#00AEEF]/20 rounded-full"></div>
                                    <div class="absolute w-24 h-24 border-2 border-[dashed] border-[#00AEEF]/40 rounded-full animate-[spin_4s_linear_infinite]"></div>
                                    <div class="absolute w-16 h-16 border-4 border-[#00AEEF] rounded-full shadow-[0_0_20px_#00AEEF] animate-pulse"></div>
                                    <svg class="w-8 h-8 text-white z-10 relative animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                                </div>
                                
                                <h3 class="text-xl font-bold text-white text-center tracking-wide">Mengkalkulasi Probabilitas<br><span class="text-[#00AEEF] text-sm animate-pulse">Algoritma sedang menganalisa data...</span></h3>
                                
                                <div class="w-48 h-1.5 bg-slate-800 rounded-full overflow-hidden mt-4">
                                    <div class="h-full bg-gradient-to-r from-[#00AEEF] to-[#00A651] animate-[progress_1s_ease-in-out_infinite] w-2/3"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Result Ready State -->
                        <div v-else-if="result" class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-700 flex flex-col min-h-[550px] animate-fade-in-up">
                            
                            <!-- Dynamic Result Header -->
                            <div class="relative p-10 flex flex-col items-center justify-center text-center overflow-hidden transition-colors duration-500" :class="result.prediction === 1 ? 'bg-gradient-to-b from-rose-600 to-rose-700 border-b border-rose-800' : 'bg-gradient-to-b from-[#00A651] to-emerald-700 border-b border-emerald-800'">
                                <!-- Tech glow effect -->
                                <div class="absolute top-0 right-0 w-full h-full bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.2),_transparent_50%)] pointer-events-none"></div>
                                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCI+PHRleHQgeD0iMCIgeT0iMTIiIGZpbGw9InJnYmEoMjU1LCAyNTUsIDI1NSwgMC4xNSkiIGZvbnQtZmFtaWx5PSJzYW5zLXNlcmlmIiBmb250LXNpemU9IjIwcHgiPi48L3RleHQ+PC9zdmc+')] bg-[length:16px_16px] opacity-20 Mix-blend-overlay"></div>
                                
                                <div class="relative z-10 w-24 h-24 rounded-full bg-white/10 backdrop-blur-md border border-white/30 flex items-center justify-center shadow-inner mb-6 shadow-2xl" :class="result.prediction === 1 ? 'shadow-rose-900/50' : 'shadow-emerald-900/50'">
                                    <svg v-if="result.prediction === 1" class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    <svg v-else class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <h3 class="relative z-10 text-[11px] font-black text-white/70 uppercase tracking-[0.2em] mb-1">Hasil Prediksi Sistem</h3>
                                <p class="relative z-10 text-4xl font-extrabold text-white tracking-tight drop-shadow-md">
                                    {{ result.prediction === 1 ? 'BERESIKO' : 'A M A N' }}
                                </p>
                                <p class="relative z-10 text-sm font-medium text-white/80 mt-2">Naive Bayes Classification</p>
                            </div>

                            <!-- Diagnostic Details -->
                            <div class="flex-1 p-8 flex flex-col justify-between bg-white dark:bg-slate-800 relative">
                                
                                <div>
                                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <svg class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        Logaritma Probabilitas Posterior
                                    </h4>

                                    <!-- Visual Probability Chart/Bars -->
                                    <div class="space-y-6">
                                        
                                        <!-- Risk Probability (Class 1) -->
                                        <div class="relative">
                                            <div class="flex justify-between items-end mb-2">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-rose-500">Probabilitas Berisiko (1)</span>
                                                    <span class="text-[10px] font-mono text-slate-400 mt-0.5" v-if="log_probabilities">Log: {{ log_probabilities[1].toFixed(4) }}</span>
                                                </div>
                                                <span class="text-lg font-black text-rose-600">{{ (probabilities[1] * 100).toFixed(6) }}%</span>
                                            </div>
                                            <!-- Dynamic Bar -->
                                            <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden shadow-inner border border-slate-200">
                                                <!-- If it's effectively 0 or very small in display, ensure it's visible if it's the winner, otherwise just render width -->
                                                <div class="h-full bg-gradient-to-r from-rose-400 to-rose-500 transition-all duration-1000 ease-out" :style="{ width: Math.max((probabilities[1] * 100), 1) + '%' }"></div>
                                            </div>
                                        </div>

                                        <!-- Safe Probability (Class 0) -->
                                        <div class="relative">
                                            <div class="flex justify-between items-end mb-2">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-[#00A651]">Probabilitas Aman (0)</span>
                                                    <span class="text-[10px] font-mono text-slate-400 mt-0.5" v-if="log_probabilities">Log: {{ log_probabilities[0].toFixed(4) }}</span>
                                                </div>
                                                <span class="text-lg font-black text-[#00A651]">{{ (probabilities[0] * 100).toFixed(6) }}%</span>
                                            </div>
                                            <!-- Dynamic Bar -->
                                            <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden shadow-inner border border-slate-200">
                                                <div class="h-full bg-gradient-to-r from-[#00A651] to-emerald-500 transition-all duration-1000 ease-out" :style="{ width: Math.max((probabilities[0] * 100), 1) + '%' }"></div>
                                            </div>
                                        </div>

                                    </div>
                                    
                                    <!-- Explaination box -->
                                    <div class="mt-8 p-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl flex items-start gap-3">
                                        <svg class="h-5 w-5 text-indigo-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <p class="text-[11px] font-semibold text-slate-500 leading-relaxed uppercase tracking-wider">
                                            Kesimpulan prediksi diambil dengan membandingkan persentase Berisiko vs Aman. Hasil akhir ditentukan oleh nilai persentase yang paling besar.
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Button to clear/print -->
                                <button type="button" @click="resetPrediction" class="w-full mt-8 flex justify-center py-3.5 px-4 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-700 border-2 border-slate-200 dark:border-slate-600 hover:border-slate-300 dark:hover:border-slate-500 hover:bg-slate-50 dark:hover:bg-slate-600 transition-all focus:outline-none">
                                    Lakukan Prediksi Baru
                                </button>
                            </div>
                        </div>

                        <!-- Initial Empty State -->
                        <div v-else class="bg-[#0A192F] rounded-3xl p-10 flex flex-col items-center justify-center min-h-[550px] shadow-2xl border border-slate-800 relative overflow-hidden group">
                           <!-- Awesome empty state background graphic -->
                           <div class="absolute inset-0 bg-gradient-to-b from-[#0A192F] to-[#061121] z-0"></div>
                           <div class="absolute inset-x-0 -top-20 h-64 bg-gradient-to-b from-blue-900/10 to-transparent z-0 opacity-50 group-hover:opacity-100 transition-opacity duration-[2s]"></div>

                            <div class="relative z-10 w-32 h-32 mb-8 text-slate-800">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full text-slate-800 drop-shadow-[0_0_15px_rgba(255,255,255,0.05)]">
                                    <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                </svg>
                            </div>
                            
                            <h3 class="text-xl font-bold text-white mb-2 relative z-10 text-center tracking-tight">Menunggu Analisis</h3>
                            <p class="text-slate-400 text-center font-medium text-sm relative z-10 max-w-[250px] leading-relaxed">Isi form metrik kesehatan pasien di samping untuk memulai kalkulasi sistem.</p>
                            
                            <!-- decorative scanline -->
                            <div class="absolute inset-0 z-20 pointer-events-none opacity-5 bg-[linear-gradient(rgba(255,255,255,0)_50%,rgba(0,0,0,0.25)_50%),linear-gradient(90deg,rgba(255,0,0,0.06),rgba(0,255,0,0.02),rgba(0,0,255,0.06))] bg-[length:100%_4px,3px_100%]"></div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-up {
    animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes shine {
    100% {
        transform: translateX(200%) translateY(-50%) rotate(12deg);
    }
}

@keyframes progress {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(200%); }
}
</style>
