<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import GlobalConfirmDialog from '@/Components/GlobalConfirmDialog.vue';
import GlobalNotificationCenter from '@/Components/GlobalNotificationCenter.vue';
import { Link } from '@inertiajs/vue3';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';
import { useDarkMode } from '@/Composables/useDarkMode';

const showingNavigationDropdown = ref(false);
useDarkMode(); // Initialize dark mode on layout mount
</script>

<template>
    <div class="min-h-screen bg-slate-50 dark:bg-[#061121] bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-blue-50/40 via-slate-50 to-slate-50 dark:from-[#0A192F] dark:via-[#061121] dark:to-[#061121] font-sans text-slate-900 dark:text-slate-100 antialiased selection:bg-[#00AEEF] selection:text-white relative z-0 transition-colors duration-500">
        
        <!-- Abstract Dot Map Background behind the entire page -->
        <div class="fixed inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCI+PHRleHQgeD0iMCIgeT0iMTIiIGZpbGw9InJnYmEoMTQ4LCAxNjMsIDE4NCLCAwLjI1KSIgZm9udC1mYW1pbHk9InNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMjBweCI+LjwvdGV4dD48L3N2Zz4=')] dark:opacity-10 bg-[length:24px_24px] pointer-events-none -z-10 opacity-30 mix-blend-multiply"></div>

        <!-- Floating Modern Navbar -->
        <nav class="sticky top-0 z-50 w-full backdrop-blur-xl bg-white/80 dark:bg-[#0A192F]/80 border-b border-slate-200/80 dark:border-slate-800 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-[5.5rem] items-center">
                    
                    <!-- Left Side: Logo & Main Nav -->
                    <div class="flex items-center gap-10">
                        <!-- Modern Logo Branding -->
                        <Link href="/dashboard" class="shrink-0 flex items-center gap-3.5 cursor-pointer hover:opacity-80 transition-opacity focus:outline-none">
                            <div class="relative group">
                                <div class="absolute -inset-1 bg-gradient-to-r from-[#00AEEF] to-[#00A651] rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                                <div class="relative w-12 h-12 rounded-2xl bg-gradient-to-br from-[#00AEEF] to-[#0088cc] flex items-center justify-center shadow-lg shadow-blue-500/30 border border-white/40">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7 text-white drop-shadow-sm">
                                      <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-extrabold text-2xl tracking-tight text-slate-800 dark:text-white leading-none">Anutapura</span>
                                <span class="text-xs font-bold text-[#00A651] tracking-[0.2em] uppercase mt-1 opacity-90">Data Mining</span>
                            </div>
                        </Link>

                        <!-- Desktop Navigation Links (Pill Style) -->
                        <div class="hidden sm:flex sm:items-center sm:gap-2 p-1.5 bg-slate-100/60 dark:bg-slate-800/60 rounded-full border border-slate-200/60 dark:border-slate-700/60 shadow-inner">
                            <Link :href="route('dashboard')"
                                :class="[
                                    route().current('dashboard') ? 'bg-white dark:bg-slate-900 text-[#00AEEF] shadow-sm shadow-slate-200 dark:shadow-black border-white dark:border-slate-700 ring-1 ring-slate-100 dark:ring-slate-800' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/50 dark:hover:bg-slate-800 border-transparent',
                                    'px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 ease-out border'
                                ]">
                                Dashboard
                            </Link>

                            <Link v-if="$page.props.auth.user.id === 1" :href="route('dataset.index')"
                                :class="[
                                    route().current('dataset.*') ? 'bg-white dark:bg-slate-900 text-[#00AEEF] shadow-sm shadow-slate-200 dark:shadow-black border-white dark:border-slate-700 ring-1 ring-slate-100 dark:ring-slate-800' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/50 dark:hover:bg-slate-800 border-transparent',
                                    'px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 ease-out flex items-center gap-2 border'
                                ]">
                                Dataset Center
                            </Link>

                            <Link :href="route('prediction.index')"
                                :class="[
                                    route().current('prediction.*') ? 'bg-[#00A651]/10 text-[#00A651] shadow-sm shadow-[#00A651]/10 ring-1 ring-[#00A651]/20 border-transparent' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/50 dark:hover:bg-slate-800 border-transparent',
                                    'px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-300 ease-out flex items-center gap-2 border'
                                ]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4" :class="route().current('prediction.*') ? 'text-[#00A651]' : 'text-slate-400'"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                                Prediksi Pasien
                            </Link>
                        </div>
                    </div>

                    <!-- Right Side: Dark Mode & User Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:gap-6">
                        <!-- Dark Mode Toggle Button -->
                        <DarkModeToggle />

                        <div class="relative">
                            <Dropdown align="right" width="56">
                                <template #trigger>
                                    <button type="button" class="group flex items-center gap-3 pr-4 p-1.5 py-1.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#00AEEF]/30 ring-offset-2 ring-offset-slate-50 dark:ring-offset-[#0A192F]">
                                        <!-- Real Avatar via API -->
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-slate-500 font-bold overflow-hidden border border-slate-100 dark:border-slate-700 shadow-sm transition-transform group-hover:scale-105 shrink-0">
                                            <img :src="`https://ui-avatars.com/api/?name=${$page.props.auth.user.name}&background=0A192F&color=fff&rounded=true&bold=true`" alt="Avatar" class="w-full h-full object-cover"/>
                                        </div>
                                        <div class="flex flex-col items-start pt-0.5 max-w-[120px]">
                                            <span class="text-[13px] font-bold text-slate-700 dark:text-slate-200 leading-none group-hover:text-[#00AEEF] dark:group-hover:text-[#00AEEF] transition-colors truncate w-full text-left">{{ $page.props.auth.user.name }}</span>
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium tracking-wide mt-1 uppercase">{{ $page.props.auth.user.id === 1 ? 'Administrator' : 'Staf Medis' }}</span>
                                        </div>
                                        <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 ml-1 group-hover:text-slate-500 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <div class="p-2 font-sans">
                                        <div class="px-4 py-3 bg-slate-50 rounded-xl mb-1.5 border border-slate-100/50">
                                            <p class="text-[10px] font-black uppercase tracking-widest text-[#00AEEF] mb-0.5">Signed In As</p>
                                            <p class="text-sm font-bold text-slate-800 truncate" :title="$page.props.auth.user.email">{{ $page.props.auth.user.email }}</p>
                                        </div>
                                        <DropdownLink :href="route('profile.edit')" class="rounded-xl mb-1 flex items-center gap-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-bold text-sm py-2.5 transition-colors">
                                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            Pengaturan Akun
                                        </DropdownLink>
                                        <div class="border-t border-slate-100 my-1"></div>
                                        <DropdownLink :href="route('logout')" method="post" as="button" class="rounded-xl flex items-center gap-2.5 text-rose-600 hover:text-rose-700 hover:bg-rose-50 font-bold text-sm py-2.5 transition-colors">
                                            <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                            Keluar Sistem
                                        </DropdownLink>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Hamburger Mobile -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-[#00AEEF] transition duration-200">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Dropdown Menu -->
            <div :class="{ 'max-h-96 opacity-100': showingNavigationDropdown, 'max-h-0 opacity-0': !showingNavigationDropdown }" class="sm:hidden overflow-hidden transition-all duration-300 ease-in-out bg-white/95 backdrop-blur-xl border-b border-slate-200 shadow-xl shadow-slate-200/50 absolute w-full z-50">
                <div class="pt-2 pb-4 space-y-1 px-4">
                    <Link :href="route('dashboard')" :class="[route().current('dashboard') ? 'bg-[#00AEEF]/10 border-l-4 border-[#00AEEF] text-[#00AEEF]' : 'text-slate-600 hover:bg-slate-50 border-transparent', 'block pl-4 pr-4 py-3.5 font-bold text-base rounded-r-xl transition-all']">Dashboard</Link>
                    <Link v-if="$page.props.auth.user.id === 1" :href="route('dataset.index')" :class="[route().current('dataset.*') ? 'bg-[#00AEEF]/10 border-l-4 border-[#00AEEF] text-[#00AEEF]' : 'text-slate-600 hover:bg-slate-50 border-transparent', 'block pl-4 pr-4 py-3.5 font-bold text-base rounded-r-xl transition-all']">Manajemen Dataset</Link>
                    <Link :href="route('prediction.index')" :class="[route().current('prediction.*') ? 'bg-[#00A651]/10 border-l-4 border-[#00A651] text-[#00A651]' : 'text-slate-600 hover:bg-slate-50 border-transparent', 'block pl-4 pr-4 py-3.5 font-bold text-base rounded-r-xl transition-all']">Prediksi Medis</Link>
                </div>
                <div class="pt-5 pb-3 border-t border-slate-100 bg-slate-50">
                    <div class="px-5 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden shadow-sm border-2 border-white shrink-0">
                            <img :src="`https://ui-avatars.com/api/?name=${$page.props.auth.user.name}&background=0A192F&color=fff&rounded=true&bold=true`" alt="Avatar" class="w-full h-full object-cover"/>
                        </div>
                        <div class="min-w-0">
                            <div class="font-bold text-base text-slate-800 truncate">{{ $page.props.auth.user.name }}</div>
                            <div class="font-medium text-sm text-slate-500 truncate">{{ $page.props.auth.user.email }}</div>
                        </div>
                    </div>
                    <div class="mt-4 px-4 pb-2 space-y-1">
                        <Link :href="route('profile.edit')" class="block px-4 py-3 text-sm font-bold text-slate-700 bg-white rounded-xl shadow-sm border border-slate-100">Pengaturan Akun</Link>
                        <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-4 py-3 text-sm font-bold text-rose-600 bg-white rounded-xl shadow-sm border border-slate-100">Keluar Sistem</Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Dynamic Header Slot Container -->
        <header class="pt-10 pb-4 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" v-if="$slots.header">
            <div class="animate-fade-in-up">
                <slot name="header" />
            </div>
        </header>

        <!-- Main View Context -->
        <main class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative animate-fade-in-up">
                <slot />
            </div>
        </main>
        
        <!-- Subtle App Footer -->
        <footer class="text-center pb-8 pt-16 relative z-10">
            <p class="text-slate-400 text-xs font-bold tracking-wider">
                &copy; {{ new Date().getFullYear() }} RS ANUTAPURA PALU • INTELLIGENT HEALTH MINING
            </p>
        </footer>
        <GlobalNotificationCenter top-offset="6rem" />
        <GlobalConfirmDialog />
    </div>
</template>

<style>
/* Global Premium Micro Animations */
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
    animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Beautiful Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
