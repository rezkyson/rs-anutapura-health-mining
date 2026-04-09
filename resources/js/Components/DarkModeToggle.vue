<script setup>
import { useDarkMode } from '@/Composables/useDarkMode';

const { isDark, toggleDark } = useDarkMode();
</script>

<template>
    <button 
        @click="toggleDark" 
        class="relative w-16 h-8 rounded-full focus:outline-none focus:ring-2 focus:ring-[#00AEEF]/50 shadow-inner overflow-hidden transition-colors duration-500 ease-in-out"
        :class="isDark ? 'bg-slate-800 border border-slate-700' : 'bg-blue-100/50 border border-blue-200'"
        aria-label="Toggle Dark Mode"
    >
        <!-- Background Sky for Light Mode (Clouds) -->
        <div class="absolute inset-0 w-full h-full opacity-100 transition-opacity duration-300 pointer-events-none" :class="{ 'opacity-0': isDark }">
            <div class="absolute top-[3px] left-[6px] w-[6px] h-[2px] bg-white rounded-full"></div>
            <div class="absolute top-[6px] left-[10px] w-[8px] h-[2px] bg-white rounded-full"></div>
            <div class="absolute top-[8px] left-[4px] w-[4px] h-[2px] bg-white rounded-full"></div>
            <div class="absolute bottom-[4px] right-[10px] w-[12px] h-[2px] bg-white/70 rounded-full"></div>
            <div class="absolute bottom-[2px] right-[6px] w-[8px] h-[2px] bg-white/70 rounded-full"></div>
        </div>

        <!-- Background Space for Dark Mode (Stars) -->
        <div class="absolute inset-0 w-full h-full opacity-0 transition-opacity duration-300 pointer-events-none" :class="{ 'opacity-100': isDark }">
            <div class="absolute top-[4px] left-[6px] w-[1.5px] h-[1.5px] bg-white rounded-full shadow-[0_0_2px_#fff] animate-pulse"></div>
            <div class="absolute top-[8px] left-[14px] w-[1px] h-[1px] bg-white rounded-full shadow-[0_0_2px_#fff]"></div>
            <div class="absolute top-[18px] left-[8px] w-[2px] h-[2px] bg-white rounded-full shadow-[0_0_3px_#fff] animate-pulse"></div>
            <div class="absolute top-[12px] right-[8px] w-[1.5px] h-[1.5px] bg-white rounded-full shadow-[0_0_2px_#fff]"></div>
        </div>

        <!-- The Thumb (Sun / Moon) -->
        <div 
            class="absolute top-0.5 left-0.5 w-7 h-7 rounded-full flex items-center justify-center transform transition-all duration-500 ease-[cubic-bezier(0.68,-0.55,0.265,1.55)]"
            :class="[
                isDark 
                ? 'translate-x-[32px] bg-slate-900 shadow-[inset_-3px_-2px_1px_0_rgba(255,255,255,0.3),_0_0_10px_rgba(255,255,255,0.4)]' 
                : 'translate-x-0 bg-gradient-to-br from-amber-200 to-orange-400 shadow-[0_0_10px_rgba(251,191,36,0.6)]'
            ]"
        >
            <!-- Sun Rays (v-show in Light Mode) -->
            <svg v-if="!isDark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4 text-white drop-shadow-sm transition-transform duration-500" :class="{ 'rotate-[360deg]': isDark }">
                <circle cx="12" cy="12" r="4"></circle>
                <path d="M12 2v2"></path>
                <path d="M12 20v2"></path>
                <path d="M4.93 4.93l1.41 1.41"></path>
                <path d="M17.66 17.66l1.41 1.41"></path>
                <path d="M2 12h2"></path>
                <path d="M20 12h2"></path>
                <path d="M6.34 17.66l-1.41 1.41"></path>
                <path d="M19.07 4.93l-1.41 1.41"></path>
            </svg>

            <!-- Moon Craters (v-show in Dark Mode) -->
            <div v-else class="w-full h-full relative overflow-hidden rounded-full transition-transform duration-500 transform" :class="{ 'rotate-[-360deg]': !isDark }">
                 <div class="absolute top-[5px] left-[6px] w-[5px] h-[5px] rounded-full bg-slate-700/50 shadow-[inset_1px_1px_2px_rgba(0,0,0,0.5)]"></div>
                 <div class="absolute bottom-[4px] right-[6px] w-[6px] h-[6px] rounded-full bg-slate-700/40 shadow-[inset_1px_1px_2px_rgba(0,0,0,0.5)]"></div>
                 <div class="absolute top-[12px] left-[4px] w-[3px] h-[3px] rounded-full bg-slate-700/60 shadow-[inset_0.5px_0.5px_1px_rgba(0,0,0,0.5)]"></div>
            </div>
        </div>
    </button>
</template>
