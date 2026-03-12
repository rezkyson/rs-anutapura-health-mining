<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    prediction_result: Object
});

const form = useForm({
    age: '',
    gender: 'L',
    blood_pressure: '',
    cholesterol: '',
    blood_sugar: ''
});

const submitPrediction = () => {
    form.post(route('prediction.predict'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Pemeriksaan Risiko Jantung - RS Anutapura Palu" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold leading-tight text-gray-800 tracking-tight">
                    <span class="text-[#00AEEF]">Tenaga Medis</span> <span class="text-gray-400 font-normal mx-2">/</span> Skrining Risiko Penyakit Jantung
                </h2>
                <!-- Decorative Badge -->
                <span class="hidden sm:inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-[#00AEEF] ring-1 ring-inset ring-blue-500/10">
                    Sistem Pendukung Keputusan
                </span>
            </div>
        </template>

        <div class="py-12 bg-gray-50/50 min-h-screen">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8">
                
                <!-- Left Column: Form Section -->
                <div class="w-full lg:w-7/12 transition-all duration-300">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-[#00AEEF] to-[#0088cc] px-8 py-6 text-white">
                            <h3 class="text-xl font-semibold flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Form Parameter Klinis Pasien
                            </h3>
                            <p class="mt-2 text-blue-100 text-sm font-medium">Berdasarkan model Algoritma Naive Bayes.</p>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="p-8">
                            <form @submit.prevent="submitPrediction" class="space-y-6">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Umur -->
                                    <div class="relative group">
                                        <InputLabel for="age" value="Usia Pasien" class="text-gray-600 mb-1.5 block font-semibold" />
                                        <div class="relative flex items-center">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#00AEEF] transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <input
                                                id="age"
                                                type="number"
                                                v-model="form.age"
                                                class="block w-full pl-10 pr-12 py-3 border-gray-200 rounded-xl focus:border-[#00AEEF] focus:ring focus:ring-[#00AEEF] focus:ring-opacity-20 transition-all shadow-sm bg-gray-50 focus:bg-white"
                                                placeholder="Contoh: 45"
                                                required
                                                min="1"
                                                max="150"
                                            />
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-400 sm:text-sm">Tahun</span>
                                            </div>
                                        </div>
                                        <InputError class="mt-2" :message="form.errors.age" />
                                    </div>

                                    <!-- Jenis Kelamin -->
                                    <div class="relative group">
                                        <InputLabel for="gender" value="Jenis Kelamin" class="text-gray-600 mb-1.5 block font-semibold" />
                                        <div class="relative">
                                            <select
                                                id="gender"
                                                v-model="form.gender"
                                                class="block w-full py-3 pl-4 pr-10 border-gray-200 rounded-xl focus:border-[#00AEEF] focus:ring focus:ring-[#00AEEF] focus:ring-opacity-20 transition-all shadow-sm bg-gray-50 focus:bg-white appearance-none"
                                                required
                                            >
                                                <option value="L">Laki-laki (L)</option>
                                                <option value="P">Perempuan (P)</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                        <InputError class="mt-2" :message="form.errors.gender" />
                                    </div>
                                </div>

                                <!-- Sistolik -->
                                <div class="relative group">
                                    <InputLabel for="blood_pressure" value="Tekanan Darah Sistolik" class="text-gray-600 mb-1.5 block font-semibold" />
                                    <div class="relative flex items-center">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#00AEEF] transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </div>
                                        <input
                                            id="blood_pressure"
                                            type="number"
                                            v-model="form.blood_pressure"
                                            class="block w-full pl-10 pr-16 py-3 border-gray-200 rounded-xl focus:border-[#00AEEF] focus:ring focus:ring-[#00AEEF] focus:ring-opacity-20 transition-all shadow-sm bg-gray-50 focus:bg-white"
                                            placeholder="Normalnya: 120"
                                            required
                                            min="30"
                                            max="250"
                                        />
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                            <span class="text-gray-400 sm:text-sm font-medium">mmHg</span>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-400 text-right">Nilai sistol pada saat pemeriksaan</p>
                                    <InputError class="mt-2" :message="form.errors.blood_pressure" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Kolesterol -->
                                    <div class="relative group">
                                        <InputLabel for="cholesterol" value="Total Kolesterol" class="text-gray-600 mb-1.5 block font-semibold" />
                                        <div class="relative flex items-center">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#00AEEF] transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                </svg>
                                            </div>
                                            <input
                                                id="cholesterol"
                                                type="number"
                                                v-model="form.cholesterol"
                                                class="block w-full pl-10 pr-16 py-3 border-gray-200 rounded-xl focus:border-[#00AEEF] focus:ring focus:ring-[#00AEEF] focus:ring-opacity-20 transition-all shadow-sm bg-gray-50 focus:bg-white"
                                                placeholder="Contoh: 200"
                                                required
                                                min="50"
                                                max="600"
                                            />
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                <span class="text-gray-400 sm:text-sm font-medium">mg/dL</span>
                                            </div>
                                        </div>
                                        <InputError class="mt-2" :message="form.errors.cholesterol" />
                                    </div>

                                    <!-- Gula Darah -->
                                    <div class="relative group">
                                        <InputLabel for="blood_sugar" value="Gula Darah Puasa" class="text-gray-600 mb-1.5 block font-semibold" />
                                        <div class="relative flex items-center">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#00AEEF] transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <input
                                                id="blood_sugar"
                                                type="number"
                                                v-model="form.blood_sugar"
                                                class="block w-full pl-10 pr-16 py-3 border-gray-200 rounded-xl focus:border-[#00AEEF] focus:ring focus:ring-[#00AEEF] focus:ring-opacity-20 transition-all shadow-sm bg-gray-50 focus:bg-white"
                                                placeholder="Contoh: 90"
                                                required
                                                min="30"
                                                max="500"
                                            />
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                <span class="text-gray-400 sm:text-sm font-medium">mg/dL</span>
                                            </div>
                                        </div>
                                        <InputError class="mt-2" :message="form.errors.blood_sugar" />
                                    </div>
                                </div>

                                <div class="pt-6 border-t border-gray-100 flex items-center justify-end">
                                    <button 
                                        type="submit"
                                        :disabled="form.processing"
                                        class="inline-flex items-center px-6 py-3.5 bg-[#00AEEF] border border-transparent rounded-xl font-semibold text-white tracking-widest hover:bg-[#0098d1] focus:bg-[#0088cc] active:bg-[#007ab8] focus:outline-none focus:ring-2 focus:ring-[#00AEEF] focus:ring-offset-2 transition-all duration-300 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed group w-full justify-center md:w-auto text-base"
                                    >
                                        <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span v-if="!form.processing">Proses Analisa Risiko</span>
                                        <span v-else>Memproses Algoritma...</span>
                                        <svg v-if="!form.processing" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Result & Info Block -->
                <div class="w-full lg:w-5/12 flex flex-col gap-6">
                    
                    <!-- Prediction Result Alert / Card -->
                    <div 
                        class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-t-8 transition-all duration-700 ease-in-out relative"
                        :class="[
                            $page.props.flash?.prediction_result 
                                ? ($page.props.flash.prediction_result.prediction_value === 1 ? 'border-red-500 bg-red-50/10 opacity-100 scale-100' : 'border-[#00A651] bg-[#00A651]/5 opacity-100 scale-100')
                                : 'opacity-60 grayscale blur-[1px]'
                        ]"
                    >
                        <!-- Top decorative bg pattern -->
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full opacity-10"
                             :class="$page.props.flash?.prediction_result && $page.props.flash.prediction_result.prediction_value === 1 ? 'bg-red-500' : 'bg-[#00A651]'">
                        </div>

                        <div class="p-8 relative z-10">
                            <!-- Helper text if no result yet -->
                            <div v-if="!$page.props.flash?.prediction_result" class="text-center py-10">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-20 h-20 mx-auto text-gray-300 mb-4">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-500">Menunggu Input Data</h3>
                                <p class="text-sm text-gray-400 mt-2">Isi form di samping untuk melihat hasil analisa probabilitas risiko jantung pasien.</p>
                            </div>

                            <!-- Success / Danger Result Container -->
                            <div v-else class="animate-fade-in-up">
                                <div class="flex items-start justify-between">
                                    <div class="flex-shrink-0 mt-2">
                                        <!-- Beresiko Icon -->
                                        <div v-if="$page.props.flash.prediction_result.prediction_value === 1" class="bg-red-100 p-4 rounded-2xl shadow-inner">
                                            <svg class="h-10 w-10 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <!-- Tidak Beresiko Icon -->
                                        <div v-else class="bg-green-100 p-4 rounded-2xl shadow-inner">
                                            <svg class="h-10 w-10 text-[#00A651]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-5 flex-1">
                                        <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Kesimpulan Sistem</span>
                                        <h3 class="text-3xl font-black mt-1" :class="$page.props.flash.prediction_result.prediction_value === 1 ? 'text-red-700' : 'text-[#00A651]'">
                                            {{ $page.props.flash.prediction_result.prediction_label }}
                                        </h3>
                                        <div class="mt-3 text-sm font-medium" :class="$page.props.flash.prediction_result.prediction_value === 1 ? 'text-red-600' : 'text-green-700'">
                                            <p v-if="$page.props.flash.prediction_result.prediction_value === 1">Pasien menunjukkan probabilitas tinggi terhadap risiko penyakit kardiovaskular. Diperlukan tindakan/pemeriksaan lebih lanjut.</p>
                                            <p v-else>Pasien menunjukkan probabilitas yang aman terhadap risiko penyakit kardiovaskular berdasarkan parameter ini.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Info Probabilitas Log -->
                                <div class="mt-8 bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center justify-between">
                                        <span>Metrik Probabilitas (Log)</span>
                                        <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded text-[10px]">Naive Bayes</span>
                                    </h4>
                                    <div class="flex flex-col gap-3">
                                        <div class="relative pt-1">
                                            <div class="flex mb-2 items-center justify-between">
                                                <div>
                                                    <span class="text-xs font-semibold inline-block text-red-600">
                                                        Beresiko
                                                    </span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-xs font-bold inline-block text-red-600">
                                                        {{ $page.props.flash.prediction_result.probabilities.beresiko_score.toFixed(4) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded-full bg-red-100">
                                                <!-- Visual bar length is relative, but since these are negative logs, we just show a static or scaled representation. Using 100% / 50% for illustration. -->
                                                <div :style="{ width: $page.props.flash.prediction_result.prediction_value === 1 ? '100%' : '30%' }" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500 transition-all duration-1000"></div>
                                            </div>
                                        </div>
                                        <div class="relative pt-1 border-t border-gray-50 pt-3">
                                            <div class="flex mb-2 items-center justify-between">
                                                <div>
                                                    <span class="text-xs font-semibold inline-block text-[#00A651]">
                                                        Tidak Beresiko
                                                    </span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-xs font-bold inline-block text-[#00A651]">
                                                        {{ $page.props.flash.prediction_result.probabilities.tidak_beresiko_score.toFixed(4) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded-full bg-green-100">
                                                <div :style="{ width: $page.props.flash.prediction_result.prediction_value === 0 ? '100%' : '30%' }" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-[#00A651] transition-all duration-1000"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-2 text-center">*Sistem membandingkan logaritma dari probabilitas klasifikasi (Prior & Likelihood).</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Medical Disclaimer (Always Visible) -->
                    <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-100 rounded-2xl p-6 shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0 bg-white p-2 rounded-full shadow-sm">
                                <svg class="h-6 w-6 text-[#00AEEF]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-base font-semibold text-blue-900">
                                    Informasi Pendukung Klinis
                                </h3>
                                <div class="mt-2 text-sm text-blue-800 text-justify leading-relaxed opacity-90">
                                    <p>Sistem ini merupakan implementasi <strong>Algoritma Naive Bayes</strong> yang dilatih menggunakan dataset historis pasien di RS Anutapura Palu.</p>
                                    <p class="mt-2">Nilai yang dihasilkan bertindak mendampingi sebagai pendukung pertimbangan medis spesialis. <strong class="text-[#00AEEF]">Sistem ini bukan diagnosa mutlak/pengganti keputusan medis dokter</strong>.</p>
                                </div>
                            </div>
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
    animation: fadeInUp 0.5s ease-out forwards;
}
</style>
