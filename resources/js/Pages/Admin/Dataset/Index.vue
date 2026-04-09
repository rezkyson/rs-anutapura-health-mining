<script setup>
import { ref } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    datasets: Object,
    stats: Object,
});

const fileInput = ref(null);
const isDragging = ref(false);
const isUploading = ref(false);

const handleFileUpload = (e) => {
    let file = e.target.files ? e.target.files[0] : e.dataTransfer.files[0];
    if (!file) return;

    if (file.type !== 'text/csv' && !file.name.endsWith('.csv')) {
        alert('Format file tidak didukung! Pastikan file berformat .csv');
        return;
    }

    isUploading.value = true;
    router.post(route('dataset.import'), { file: file }, {
        preserveScroll: true,
        onSuccess: () => {
            isUploading.value = false;
            if (fileInput.value) fileInput.value.value = '';
        },
        onError: () => {
            isUploading.value = false;
        }
    });
};

const triggerFileInput = () => {
    fileInput.value.click();
};

const toggleTrainingStatus = (id, currentStatus) => {
    router.put(route('dataset.toggle-training', id), {
        is_training: !currentStatus
    }, { preserveScroll: true });
};

const defaultClassBackground = (val) => {
    return val === 1 ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-[#00A651]/10 text-[#00A651] border-[#00A651]/20';
};

const isSplitting = ref(false);
const autoSplit = () => {
    if (!confirm('Sistem akan membagi dataset secara acak: 80% Data Latih dan 20% Data Uji. Lanjutkan?')) return;
    isSplitting.value = true;
    router.post(route('dataset.autoSplit'), { ratio: 80 }, {
        preserveScroll: true,
        onFinish: () => isSplitting.value = false
    });
};
</script>

<template>
    <Head title="Manajemen Dataset Medis" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-2">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pusat Data Medis</h2>
                    <p class="text-sm font-medium text-slate-500 mt-0.5">Kelola Dataset CSV Klasifikasi Penyakit Jantung</p>
                </div>
                
                <!-- Action Header (Clear All) -->
                <div class="mt-4 sm:mt-0 flex items-center gap-3" v-if="stats.total > 0">
                    <button @click="autoSplit" :disabled="isSplitting" class="group flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#00AEEF] to-[#00A651] hover:from-[#00AEEF]/90 hover:to-[#00A651]/90 rounded-xl text-white font-bold text-sm transition-all shadow-md shadow-[#00AEEF]/20 focus:outline-none focus:ring-2 focus:ring-[#00AEEF]/50 disabled:opacity-50">
                        <svg class="h-4 w-4 group-hover:scale-110 transition-transform" :class="{ 'animate-spin': isSplitting }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        {{ isSplitting ? 'Memproses...' : 'Bagi Data Otomatis (80:20)' }}
                    </button>
                    <button @click="router.delete(route('dataset.truncate'), { preserveScroll: true })" class="group flex items-center gap-2 px-4 py-2 bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 rounded-xl text-rose-600 font-bold text-sm transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-rose-500/50">
                        <svg class="h-4 w-4 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Kosongkan Data
                    </button>
                </div>
            </div>
        </template>

        <div class="space-y-8 pb-10">
            
            <!-- Top Section: Stats & Upload -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Dataset Overview Card -->
                <div class="bg-[#0A192F] text-white rounded-2xl p-6 relative overflow-hidden shadow-xl lg:col-span-1 border border-slate-800 flex flex-col justify-between group">
                    <!-- Tech background graphic -->
                    <div class="absolute -right-16 -top-16 w-64 h-64 bg-gradient-to-br from-[#00AEEF] to-[#00A651] blur-3xl mix-blend-screen opacity-20 group-hover:opacity-40 transition-opacity duration-700 pointer-events-none"></div>
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCI+PHRleHQgeD0iMCIgeT0iMTIiIGZpbGw9InJnYmEoMjU1LCAyNTUsIDI1NSwgMC4wNCkiIGZvbnQtZmFtaWx5PSJzYW5zLXNlcmlmIiBmb250LXNpemU9IjIwcHgiPi48L3RleHQ+PC9zdmc+')] bg-[length:24px_24px]"></div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-3 text-slate-300 font-bold uppercase tracking-widest text-[11px] mb-6">
                            <span class="w-2 h-2 rounded-full bg-[#00AEEF] animate-pulse"></span>
                            Statistik Server Database
                        </div>

                        <div class="flex items-end gap-2 mb-2">
                            <span class="text-5xl font-black tracking-tighter">{{ stats.total }}</span>
                            <span class="text-sm text-slate-400 font-medium mb-1">Total Entri</span>
                        </div>
                    </div>

                    <div class="relative z-10 mt-6 grid grid-cols-2 gap-4 border-t border-slate-700/50 pt-5">
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Data Latih</p>
                            <p class="text-xl font-bold text-white">{{ stats.training }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-[#00AEEF] font-bold uppercase tracking-wider mb-1">Data Uji Baru</p>
                            <p class="text-xl font-bold text-[#00AEEF]">{{ stats.testing }}</p>
                        </div>
                    </div>
                </div>

                <!-- Beautiful Drag & Drop Upload Zone -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 lg:col-span-2 shadow-sm relative overflow-hidden group">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                        <h3 class="font-bold text-slate-800">Impor Dataset Baru</h3>
                    </div>

                    <input type="file" ref="fileInput" @change="handleFileUpload" accept=".csv" class="hidden" />
                    
                    <div 
                        @dragover.prevent="isDragging = true" 
                        @dragleave.prevent="isDragging = false" 
                        @drop.prevent="handleFileUpload" 
                        @click="triggerFileInput"
                        class="w-full h-40 rounded-xl border-2 border-dashed transition-all duration-300 flex flex-col items-center justify-center cursor-pointer group hover:bg-slate-50 relative overflow-hidden"
                        :class="[
                            isDragging ? 'border-[#00AEEF] bg-blue-50/50' : 'border-slate-300 bg-slate-50/30',
                            isUploading ? 'pointer-events-none opacity-80' : ''
                        ]"
                    >
                        <!-- Animated Upload Progress Overlay -->
                        <div v-if="isUploading" class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 flex flex-col items-center justify-center">
                            <svg class="animate-spin h-8 w-8 text-[#00AEEF] mb-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm font-bold text-slate-700">Menganalisa dan Menyimpan CSV...</span>
                        </div>

                        <div v-else class="text-center transform transition-transform group-hover:-translate-y-1">
                            <div class="mx-auto flex justify-center w-12 h-12 mb-3 rounded-full bg-white shadow-sm border border-slate-100 items-center text-[#00AEEF]">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" /></svg>
                            </div>
                            <p class="text-sm font-bold text-slate-700">Klik atau letakkan file CSV di sini</p>
                            <p class="text-xs font-semibold text-slate-400 mt-1">Ukuran Maksimal Sesuai Konfigurasi PHP</p>
                        </div>
                    </div>
                    
                    <!-- Flash Message Rendering -->
                    <div v-if="$page.props.flash && $page.props.flash.success" class="mt-4 px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-sm font-bold flex items-center gap-2 animate-fade-in-up">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {{ $page.props.flash.success }}
                    </div>
                    <div v-if="$page.props.flash && $page.props.flash.error" class="mt-4 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-700 text-sm font-bold flex items-center gap-2 animate-fade-in-up">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {{ $page.props.flash.error }}
                    </div>
                </div>

            </div>

            <!-- Premium SaaS Table Area -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-base font-extrabold text-slate-800">Tinjauan Baris Dataset</h3>
                    <div class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">{{ stats.total }} Item</div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead class="bg-slate-50/50 border-b border-slate-200">
                            <tr>
                                <th class="py-4 px-6 font-bold text-slate-500 uppercase tracking-widest text-[10px] whitespace-nowrap">ID</th>
                                <th class="py-4 px-6 font-bold text-slate-500 uppercase tracking-widest text-[10px] whitespace-nowrap">Profil Pasien</th>
                                <th class="py-4 px-6 font-bold text-slate-500 uppercase tracking-widest text-[10px] whitespace-nowrap">Indikator Medis (G/TD/K)</th>
                                <th class="py-4 px-6 font-bold text-slate-500 uppercase tracking-widest text-[10px] whitespace-nowrap text-center">Kelas Aktual</th>
                                <th class="py-4 px-6 font-bold text-slate-500 uppercase tracking-widest text-[10px] whitespace-nowrap text-center">Tipe Data Set</th>
                                <th class="py-4 px-6 font-bold text-slate-500 uppercase tracking-widest text-[10px] whitespace-nowrap text-right">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="d in datasets.data" :key="d.id" class="hover:bg-slate-50/60 transition-colors group">
                                <td class="py-4 px-6 font-bold text-slate-400">#{{ d.id }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0" :class="d.gender === 'L' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-pink-50 text-pink-600 border border-pink-100'">
                                            {{ d.gender === 'L' ? 'L' : 'P' }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-700">{{ d.age }} Tahun</span>
                                            <span class="text-[10px] uppercase font-bold text-slate-400">{{ d.gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2 font-mono text-xs">
                                        <span class="px-2 py-1 bg-slate-100 rounded border border-slate-200 text-slate-600 font-bold" title="Gula Darah">{{ d.blood_sugar }}</span>
                                        <span class="text-slate-300">/</span>
                                        <span class="px-2 py-1 bg-slate-100 rounded border border-slate-200 text-slate-600 font-bold" title="Tekanan Darah">{{ d.blood_pressure }}</span>
                                        <span class="text-slate-300">/</span>
                                        <span class="px-2 py-1 bg-slate-100 rounded border border-slate-200 text-slate-600 font-bold" title="Kolesterol">{{ d.cholesterol }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border border-transparent" :class="defaultClassBackground(d.risk_result)">
                                        {{ d.risk_result === 1 ? 'Beresiko (1)' : 'Aman (0)' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <button @click="toggleTrainingStatus(d.id, d.is_training)" class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border hover:scale-105 transition-transform shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-slate-200" :class="d.is_training ? 'bg-indigo-50 text-indigo-600 border-indigo-100 hover:bg-indigo-100' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50'">
                                        {{ d.is_training ? 'Latih (Train)' : 'Uji (Test)' }}
                                    </button>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <button @click="router.delete(route('dataset.destroy', d.id), { preserveScroll: true })" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors focus:outline-none">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="datasets.data.length === 0">
                                <td colspan="6" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-60">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>
                                        <p class="text-sm font-bold text-slate-500">Database Kosong</p>
                                        <p class="text-xs text-slate-400 mt-1 font-medium">Silakan impor file berformat .csv terlebih dahulu.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Premium Pagination -->
                <div v-if="datasets.links && datasets.links.length > 3" class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Menampilkan Hal. {{ datasets.current_page }} dari {{ datasets.last_page }}</span>
                    <nav class="flex gap-1">
                        <template v-for="(link, k) in datasets.links" :key="k">
                            <span v-if="link.url === null" class="px-3 py-1.5 rounded-lg text-sm text-slate-300 font-bold bg-white border border-slate-200 cursor-not-allowed" v-html="link.label"></span>
                            <Link v-else :href="link.url" class="px-3 py-1.5 rounded-lg text-sm font-bold transition-colors focus:outline-none focus:ring-2 focus:ring-[#00AEEF] focus:ring-offset-1 focus:ring-offset-slate-50" :class="link.active ? 'bg-[#00AEEF] text-white border-transparent shadow-[0_2px_10px_-3px_rgba(0,174,239,0.5)]' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100 hover:text-slate-900'" v-html="link.label"></Link>
                        </template>
                    </nav>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-up {
    animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
