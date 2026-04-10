<script setup>
import GlobalConfirmDialog from '@/Components/GlobalConfirmDialog.vue';
import GlobalNotificationCenter from '@/Components/GlobalNotificationCenter.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen bg-white font-sans text-slate-900 antialiased selection:bg-[#00AEEF] selection:text-white">
        
        <!-- Left Banner (Hidden on Mobile) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-[#0A192F] items-center justify-center overflow-hidden">
            <!-- Awesome Medical Abstract Background -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-br from-[#00AEEF]/20 to-[#00A651]/20 mix-blend-multiply"></div>
                <!-- Dynamic Grid Pattern -->
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCI+PHRleHQgeD0iMCIgeT0iMTIiIGZpbGw9InJnYmEoMjU1LCAyNTUsIDI1NSwgMC4wNSkiIGZvbnQtZmFtaWx5PSJzYW5zLXNlcmlmIiBmb250LXNpemU9IjIwcHgiPi48L3RleHQ+PC9zdmc+')] opacity-30"></div>
                <!-- Abstract floating blobs -->
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-[#00AEEF] rounded-full mix-blend-overlay filter blur-[120px] opacity-40 animate-pulse"></div>
                <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-[#00A651] rounded-full mix-blend-overlay filter blur-[120px] opacity-40 animate-pulse animation-delay-2000"></div>
            </div>

            <!-- Left Content Branding -->
            <div class="relative z-10 w-full max-w-lg px-6 flex flex-col justify-center animate-fade-in-up">
                <div class="flex items-center gap-4 mb-10 group">
                    <div class="relative">
                        <div class="absolute -inset-2 bg-gradient-to-r from-[#00AEEF] to-[#00A651] rounded-2xl blur-lg opacity-40 group-hover:opacity-70 transition duration-700"></div>
                        <div class="relative w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-xl border border-white/20 flex items-center justify-center shadow-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-white">
                                <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-3xl font-extrabold text-white tracking-tight leading-none">Anutapura</h1>
                        <p class="text-[11px] font-bold text-[#00A651] tracking-[0.3em] uppercase mt-1">Health Mining System</p>
                    </div>
                </div>

                <h2 class="text-4xl lg:text-5xl font-bold text-white leading-tight mb-6">
                    Masa Depan <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#00AEEF] to-[#00A651]">Prediksi Medis</span>
                </h2>
                
                <p class="text-lg text-slate-300 leading-relaxed font-light mb-10 max-w-md">
                    Sistem Pendukung Keputusan Klinik tingkat lanjut dengan mesin Naive Bayes Classification untuk identifikasi dini risiko kardiovaskular.
                </p>

                <!-- Medical Trust Badges -->
                <div class="flex items-center gap-6 mt-4 opacity-70">
                    <div class="flex items-center gap-2 text-sm text-slate-300 font-semibold bg-white/5 px-4 py-2 rounded-lg border border-white/10">
                        <svg class="w-5 h-5 text-[#00A651]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Akurasi Tinggi
                    </div>
                    <div class="flex items-center gap-2 text-sm text-slate-300 font-semibold bg-white/5 px-4 py-2 rounded-lg border border-white/10">
                        <svg class="w-5 h-5 text-[#00AEEF]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        Aman & Rahasia
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Content (Form Wrapper) -->
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-white relative">
            <!-- Mobile Background Elements (since left side is hidden) -->
            <div class="absolute inset-0 overflow-hidden lg:hidden pointer-events-none">
                <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#00AEEF]/5 rounded-full blur-[80px]"></div>
                <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-[#00A651]/5 rounded-full blur-[80px]"></div>
            </div>

            <!-- Form Container -->
            <div class="w-full max-w-md px-6 py-12 lg:py-0 relative z-10 animate-fade-in-up flex flex-col justify-center min-h-[500px]">
                
                <!-- Mobile Branding (Hidden on Desktop) -->
                <div class="lg:hidden flex justify-center mb-10 w-full group">
                    <Link href="/" class="flex flex-col items-center">
                        <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-[#00AEEF] to-[#0088cc] flex items-center justify-center shadow-lg shadow-blue-500/30 mb-4 transform transition-transform duration-300 group-hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-white">
                                <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight leading-none text-center">RS Anutapura</h1>
                        <span class="text-[10px] font-bold text-[#00A651] tracking-[0.2em] uppercase mt-1">Health Mining</span>
                    </Link>
                </div>

                <!-- Form Dynamic Content (Login/Register views render here) -->
                <div class="w-full">
                    <slot />
                </div>

                <!-- Footer -->
                <p class="mt-12 text-center text-xs text-slate-400 font-medium tracking-wide">
                    Sistem Cerdas Perekam Medis RSAP &copy; 2026.
                </p>
            </div>
        </div>

        <GlobalNotificationCenter />
        <GlobalConfirmDialog />
    </div>
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
    animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
.animation-delay-2000 {
  animation-delay: 2s;
}
</style>
