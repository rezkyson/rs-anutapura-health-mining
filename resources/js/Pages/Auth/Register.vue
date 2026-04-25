<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Registrasi Akun Baru" />

        <div class="mb-8 text-center sm:text-left">
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Akun Baru</h2>
            <p class="text-slate-500 mt-2 font-medium">Lengkapi data untuk mengakses portal pendukung medis.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div class="space-y-1.5 focus-within:text-[#00AEEF] transition-colors">
                <InputLabel for="name" value="Nama Lengkap" class="font-bold text-slate-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <TextInput
                        id="name"
                        type="text"
                        class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:border-[#00AEEF] focus:ring-[#00AEEF] focus:ring-opacity-20 transition-all font-medium"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Dr. Andi / Admin 01"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="space-y-1.5 focus-within:text-[#00AEEF] transition-colors">
                <InputLabel for="email" value="Alamat Email" class="font-bold text-slate-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:border-[#00AEEF] focus:ring-[#00AEEF] focus:ring-opacity-20 transition-all font-medium"
                        v-model="form.email"
                        required
                        autocomplete="username"
                        placeholder="contoh@healthmining.test"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="space-y-1.5 focus-within:text-[#00AEEF] transition-colors">
                <InputLabel for="password" value="Kata Sandi" class="font-bold text-slate-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <TextInput
                        id="password"
                        type="password"
                        class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:border-[#00AEEF] focus:ring-[#00AEEF] focus:ring-opacity-20 transition-all font-medium"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="space-y-1.5 focus-within:text-[#00AEEF] transition-colors">
                <InputLabel for="password_confirmation" value="Konfirmasi Sandi" class="font-bold text-slate-700" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="block w-full pl-11 pr-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:border-[#00AEEF] focus:ring-[#00AEEF] focus:ring-opacity-20 transition-all font-medium"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="mt-8">
                <button
                    type="submit"
                    class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-emerald-500/30 text-base font-bold text-white bg-gradient-to-r from-[#00A651] to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00A651] transition-all transform hover:-translate-y-0.5"
                    :class="{ 'opacity-70 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span v-if="!form.processing">Buat Akun Sistem</span>
                    <span v-else>Memproses Pendaftaran...</span>
                </button>
            </div>
            
            <p class="mt-6 text-center text-sm text-slate-600 font-medium">
                Sudah punya akses? 
                <Link :href="route('login')" class="font-bold text-[#00AEEF] hover:text-[#0088cc] transition-colors">Masuk di sini</Link>
            </p>
        </form>
    </GuestLayout>
</template>
