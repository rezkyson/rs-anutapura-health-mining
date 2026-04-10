<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import { useConfirmDialog } from '@/Composables/useConfirmDialog';

const { confirmState, approve, cancel } = useConfirmDialog();
const isSubmitting = ref(false);

const palette = computed(() => {
    const tones = {
        primary: {
            badge: 'Aksi sistem',
            iconWrap: 'bg-sky-500/10 text-sky-600 ring-1 ring-sky-500/15 dark:bg-sky-500/15 dark:text-sky-300',
            iconPath: 'M11.25 11.25 12 11.25v5.25H12.75m-.75-8.25h.008v.008H12V8.25ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
            confirm: 'bg-gradient-to-r from-[#00AEEF] to-[#0088cc] text-white shadow-[0_16px_40px_-24px_rgba(0,174,239,0.85)] hover:from-[#009fdb] hover:to-[#007ab8] focus:ring-[#00AEEF]/40',
        },
        warning: {
            badge: 'Perhatian',
            iconWrap: 'bg-amber-500/10 text-amber-600 ring-1 ring-amber-500/15 dark:bg-amber-500/15 dark:text-amber-300',
            iconPath: 'M12 9v3.75m0 3.75h.008v.008H12v-.008ZM10.34 3.94 2.59 17.25A1.5 1.5 0 0 0 3.89 19.5h16.22a1.5 1.5 0 0 0 1.3-2.25L13.66 3.94a1.5 1.5 0 0 0-2.6 0Z',
            confirm: 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-[0_16px_40px_-24px_rgba(245,158,11,0.9)] hover:from-amber-400 hover:to-orange-500 focus:ring-amber-500/30',
        },
        danger: {
            badge: 'Tindakan permanen',
            iconWrap: 'bg-rose-500/10 text-rose-600 ring-1 ring-rose-500/15 dark:bg-rose-500/15 dark:text-rose-300',
            iconPath: 'M12 9v3.75m0 3.75h.008v.008H12v-.008ZM10.34 3.94 2.59 17.25A1.5 1.5 0 0 0 3.89 19.5h16.22a1.5 1.5 0 0 0 1.3-2.25L13.66 3.94a1.5 1.5 0 0 0-2.6 0Z',
            confirm: 'bg-gradient-to-r from-rose-500 to-red-500 text-white shadow-[0_16px_40px_-24px_rgba(244,63,94,0.9)] hover:from-rose-400 hover:to-red-500 focus:ring-rose-500/30',
        },
    };

    return tones[confirmState.tone] ?? tones.primary;
});

const closeOnEscape = (event) => {
    if (event.key === 'Escape' && confirmState.open && !isSubmitting.value) {
        cancel();
    }
};

watch(
    () => confirmState.open,
    (isOpen) => {
        document.body.style.overflow = isOpen ? 'hidden' : '';

        if (!isOpen) {
            isSubmitting.value = false;
        }
    },
);

const handleCancel = () => {
    if (isSubmitting.value) {
        return;
    }

    cancel();
};

const handleApprove = () => {
    if (isSubmitting.value) {
        return;
    }

    isSubmitting.value = true;

    window.setTimeout(() => {
        approve();
    }, 180);
};

onMounted(() => {
    document.addEventListener('keydown', closeOnEscape);
});

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = '';
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="confirmState.open" class="fixed inset-0 z-[80]">
                <div class="absolute inset-0 bg-slate-950/45" @click="handleCancel" />

                <div class="relative flex min-h-full items-center justify-center p-4 sm:p-6">
                    <Transition
                        appear
                        enter-active-class="transition duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]"
                        enter-from-class="opacity-0 translate-y-4 scale-[0.98]"
                        enter-to-class="opacity-100 translate-y-0 scale-100"
                        leave-active-class="transition duration-200 ease-in"
                        leave-from-class="opacity-100 translate-y-0 scale-100"
                        leave-to-class="opacity-0 translate-y-4 scale-[0.98]"
                    >
                        <div
                            v-if="confirmState.open"
                            class="relative w-full max-w-xl overflow-hidden rounded-[1.75rem] border border-white/80 bg-white shadow-[0_24px_72px_-38px_rgba(15,23,42,0.4)] dark:border-slate-700 dark:bg-[#081426]"
                            role="alertdialog"
                            aria-modal="true"
                        >
                            <div class="absolute inset-x-0 top-0 h-28 bg-gradient-to-br from-[#00AEEF]/20 via-white/0 to-[#00A651]/10 dark:from-[#00AEEF]/20 dark:via-transparent dark:to-[#00A651]/15" />
                            <div
                                v-if="isSubmitting"
                                class="absolute inset-x-0 bottom-0 h-1.5 overflow-hidden bg-slate-200/70 dark:bg-slate-800/80"
                            >
                                <div class="confirm-loading-bar h-full w-1/2 rounded-full bg-gradient-to-r from-[#00AEEF] via-sky-400 to-[#00A651]" />
                            </div>

                            <div class="relative p-6 sm:p-8">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-[1.35rem]" :class="palette.iconWrap">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                            <path stroke-linecap="round" stroke-linejoin="round" :d="palette.iconPath" />
                                        </svg>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-[11px] font-black uppercase tracking-[0.28em] text-slate-400 dark:text-slate-500">
                                            {{ palette.badge }}
                                        </p>
                                        <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-900 dark:text-white">
                                            {{ confirmState.title }}
                                        </h2>
                                        <p class="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300 whitespace-pre-line">
                                            {{ confirmState.message }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                    <button
                                        type="button"
                                        class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition-all hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-300/70 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:border-slate-600 dark:hover:bg-slate-800 dark:hover:text-white"
                                        :disabled="isSubmitting"
                                        :class="isSubmitting ? 'cursor-not-allowed opacity-60' : ''"
                                        @click="handleCancel"
                                    >
                                        {{ confirmState.cancelLabel }}
                                    </button>

                                    <button
                                        type="button"
                                        class="inline-flex min-w-[11rem] items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-bold transition-all focus:outline-none focus:ring-2"
                                        :class="[palette.confirm, isSubmitting ? 'cursor-wait saturate-75' : '']"
                                        :disabled="isSubmitting"
                                        @click="handleApprove"
                                    >
                                        <svg
                                            v-if="isSubmitting"
                                            class="h-4 w-4 animate-spin"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle class="opacity-30" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                            <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4Z" />
                                        </svg>
                                        <span>{{ isSubmitting ? confirmState.loadingLabel : confirmState.confirmLabel }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.confirm-loading-bar {
    animation: confirmLoading 1s ease-in-out infinite;
}

@keyframes confirmLoading {
    0% {
        transform: translateX(-110%);
    }

    100% {
        transform: translateX(220%);
    }
}
</style>
