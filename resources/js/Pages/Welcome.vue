<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="RS Anutapura - Sistem Deteksi Jantung" />

    <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#00AEEF] selection:text-white overflow-hidden bg-slate-50 font-sans">
        <!-- Abstract Animated Background (Premium Feel) -->
        <div class="absolute inset-0 z-0">
            <!-- Top Right Bubble -->
            <div class="absolute -top-[20%] -right-[10%] w-[70%] h-[70%] rounded-full bg-gradient-to-br from-[#00AEEF]/20 to-[#00A651]/10 blur-[100px] mix-blend-multiply animate-blob pointer-events-none"></div>
            <!-- Bottom Left Bubble -->
            <div class="absolute -bottom-[20%] -left-[10%] w-[60%] h-[60%] rounded-full bg-gradient-to-tr from-[#00A651]/20 to-[#00AEEF]/10 blur-[100px] mix-blend-multiply animate-blob animation-delay-2000 pointer-events-none"></div>
            <!-- Center Bubble -->
            <div class="absolute top-[20%] left-[20%] w-[50%] h-[50%] rounded-full bg-gradient-to-r from-blue-200/20 to-emerald-200/20 blur-[100px] mix-blend-multiply animate-blob animation-delay-4000 pointer-events-none"></div>
            <!-- Dynamic Grid Pattern overlay for tech feel -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCI+PHRleHQgeD0iMCIgeT0iMTIiIGZpbGw9InJnYmEoMTQ4LCAxNjMsIDE4NCLCAwLjI1KSIgZm9udC1mYW1pbHk9InNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjBweCI+LjwvdGV4dD48L3N2Zz4=')] bg-[length:40px_40px] opacity-20 pointer-events-none"></div>
            <!-- Bottom Fade to White -->
            <div class="absolute bottom-0 left-0 right-0 h-1/3 bg-gradient-to-t from-slate-50 to-transparent pointer-events-none"></div>
        </div>

        <!-- Floating Glassmorphism Nav (If auth actions are available) -->
        <div v-if="canLogin" class="absolute top-6 right-6 sm:top-10 sm:right-12 z-50">
            <div class="backdrop-blur-xl bg-white/70 px-2 py-2 rounded-full border border-white/60 shadow-lg shadow-slate-200/50 flex items-center gap-1 sm:gap-2">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="px-6 py-2.5 rounded-full font-bold text-sm text-[#00AEEF] hover:bg-white hover:shadow-sm transition-all duration-300"
                >
                    Masuk ke Dashboard
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="px-6 py-2.5 rounded-full font-bold text-sm text-slate-600 hover:text-slate-900 hover:bg-white/80 transition-all duration-300"
                    >
                        Log in
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-6 py-2.5 rounded-full font-bold text-sm text-white bg-gradient-to-r from-[#00AEEF] to-[#0088cc] shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all duration-300"
                    >
                        Register
                    </Link>
                </template>
            </div>
        </div>

        <!-- Main Hero Content -->
        <div class="relative z-10 w-full max-w-5xl px-6 lg:px-8 flex flex-col items-center text-center animate-fade-in-up">
            
            <!-- Logo Icon with glowing ring (Premium aesthetic) -->
            <div class="relative group cursor-default mb-10">
                <div class="absolute -inset-1 bg-gradient-to-r from-[#00AEEF] to-[#00A651] rounded-3xl blur-md opacity-30 group-hover:opacity-60 transition duration-1000 group-hover:duration-300"></div>
                <div class="relative w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-white flex items-center justify-center shadow-2xl border border-white/50 transform transition-transform duration-500 hover:scale-[1.03]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12 sm:w-14 sm:h-14 text-transparent bg-clip-text" style="fill: url(#gradient);">
                        <defs>
                            <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#00AEEF" />
                                <stop offset="100%" stop-color="#00A651" />
                            </linearGradient>
                        </defs>
                        <!-- Hospital/Heart Cross path -->
                        <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-800 drop-shadow-sm mb-2">
                RS Anutapura <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#00AEEF] to-[#00A651]">Health Mining</span>
            </h1>
            
            <p class="mt-6 text-lg sm:text-2xl text-slate-500 max-w-3xl mx-auto leading-relaxed font-medium">
                Sistem pendukung keputusan klinis untuk skrining awal risiko kardiovaskular. Menggunakan kekuatan Data Mining <strong class="text-slate-800">Naive Bayes</strong>.
            </p>

            <!-- Actions -->
            <div class="mt-14 flex flex-col sm:flex-row gap-5 sm:gap-6 justify-center w-full max-w-md">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="group relative inline-flex items-center justify-center px-10 py-4.5 text-lg font-bold text-white transition-all duration-300 bg-gradient-to-br from-[#00AEEF] to-[#0088cc] font-sans rounded-2xl hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00AEEF] shadow-[0_10px_40px_-10px_rgba(0,174,239,0.5)] border border-white/10"
                >
                    <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black pointer-events-none"></span>
                    <span class="relative flex items-center gap-3">
                        Masuk ke Aplikasi
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 group-hover:translate-x-1 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                    </span>
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="group relative inline-flex items-center justify-center px-10 py-4 text-lg font-bold text-white transition-all duration-300 bg-gradient-to-br from-[#00AEEF] to-[#0088cc] font-sans rounded-2xl hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00AEEF] shadow-[0_10px_40px_-10px_rgba(0,174,239,0.5)] border border-white/10 w-full sm:w-auto overflow-hidden"
                    >
                        <span class="absolute inset-0 w-full h-full bg-gradient-to-tr from-white/10 to-transparent pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity"></span>
                        <span class="relative flex items-center gap-2">
                            Akses Portal Medis
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                        </span>
                    </Link>
                </template>
            </div>
            
            <!-- Badges - Glassmorphism -->
            <div class="mt-20 flex flex-wrap justify-center gap-4 sm:gap-6 lg:gap-8 opacity-70 hover:opacity-100 transition-opacity duration-500">
                <div class="flex flex-col items-center justify-center gap-2 font-bold text-sm text-slate-700 bg-white/70 backdrop-blur-xl px-6 py-4 rounded-2xl border border-white shadow-xl shadow-slate-200/50 hover:-translate-y-1 transition-transform cursor-default">
                    <div class="p-2 bg-[#00A651]/10 rounded-xl text-[#00A651]">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span>Akurasi Tervalidasi</span>
                </div>
                <div class="flex flex-col items-center justify-center gap-2 font-bold text-sm text-slate-700 bg-white/70 backdrop-blur-xl px-6 py-4 rounded-2xl border border-white shadow-xl shadow-slate-200/50 hover:-translate-y-1 transition-transform cursor-default">
                    <div class="p-2 bg-[#00AEEF]/10 rounded-xl text-[#00AEEF]">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <span>Algoritma Klasik Handal</span>
                </div>
                <div class="flex flex-col items-center justify-center gap-2 font-bold text-sm text-slate-700 bg-white/70 backdrop-blur-xl px-6 py-4 rounded-2xl border border-white shadow-xl shadow-slate-200/50 hover:-translate-y-1 transition-transform cursor-default">
                    <div class="p-2 bg-indigo-500/10 rounded-xl text-indigo-500">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <span>Protokol Keamanan</span>
                </div>
            </div>

        </div>

    </div>
</template>

<style>
/* Blob Animation for Hero */
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
  animation: blob 10s infinite cubic-bezier(0.4, 0, 0.2, 1);
}
.animation-delay-2000 {
  animation-delay: 2s;
}
.animation-delay-4000 {
  animation-delay: 4s;
}

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
    animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
