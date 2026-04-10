<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Masuk Portal Medis" />

        <div class="mb-10 text-center sm:text-left">
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Selamat Datang</h2>
            <p class="text-slate-500 mt-2 font-medium">Silakan login untuk mengakses Sistem Pendukung Keputusan Jantung.</p>
        </div>
        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-1.5 focus-within:text-[#00AEEF] transition-colors">
                <InputLabel for="email" value="Alamat Email" class="font-bold text-slate-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                    </div>
                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:border-[#00AEEF] focus:ring-[#00AEEF] focus:ring-opacity-20 transition-all font-medium"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="admin@rsap.id"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="space-y-1.5 focus-within:text-[#00AEEF] transition-colors">
                <InputLabel for="password" value="Kata Sandi" class="font-bold text-slate-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <TextInput
                        id="password"
                        type="password"
                        class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:border-[#00AEEF] focus:ring-[#00AEEF] focus:ring-opacity-20 transition-all font-medium"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <label class="flex items-center group cursor-pointer">
                    <Checkbox name="remember" v-model:checked="form.remember" class="rounded border-slate-300 text-[#00AEEF] shadow-sm focus:ring-[#00AEEF]" />
                    <span class="ms-2 text-sm text-slate-600 font-medium group-hover:text-slate-900 transition-colors">Ingat Saya</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm font-bold text-[#00AEEF] hover:text-[#0088cc] focus:outline-none transition-colors"
                >
                    Lupa Sandi?
                </Link>
            </div>

            <div class="mt-8">
                <button
                    type="submit"
                    class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-base font-bold text-white bg-gradient-to-r from-[#00AEEF] to-[#0088cc] hover:from-[#0098d1] hover:to-[#007ab8] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00AEEF] transition-all transform hover:-translate-y-0.5"
                    :class="{ 'opacity-70 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span v-if="!form.processing">Masuk Sistem</span>
                    <span v-else>Memverifikasi...</span>
                </button>
            </div>
            
            <p class="mt-6 text-center text-sm text-slate-600 font-medium">
                Belum memiliki akses? 
                <Link :href="route('register')" class="font-bold text-[#00AEEF] hover:text-[#0088cc] transition-colors">Daftar Akun Baru</Link>
            </p>
        </form>
    </GuestLayout>
</template>
