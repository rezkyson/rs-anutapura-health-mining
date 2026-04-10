<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { useNotifications } from '@/Composables/useNotifications';

const props = defineProps({
    topOffset: {
        type: String,
        default: '1.5rem',
    },
});

const page = usePage();
const { notifications, notify, dismiss } = useNotifications();
const lastFlashToken = ref(null);

const flash = computed(() => page.props.flash ?? {});

const normalizeStatusMessage = (status) => {
    if (!status) {
        return null;
    }

    const knownMessages = {
        'verification-link-sent': 'Tautan verifikasi baru berhasil dikirim ke email Anda.',
    };

    return knownMessages[status] ?? status;
};

const palette = {
    success: {
        badge: 'Sukses',
        card: 'border-emerald-200/80 bg-white/90 dark:border-emerald-500/20 dark:bg-slate-900/90',
        glow: 'from-emerald-500/20 via-emerald-400/10 to-transparent',
        iconWrap: 'bg-emerald-500/10 text-emerald-600 ring-1 ring-emerald-500/15 dark:bg-emerald-500/15 dark:text-emerald-300',
        title: 'text-emerald-700 dark:text-emerald-300',
        message: 'text-slate-600 dark:text-slate-300',
        progress: 'from-emerald-500 to-[#00A651]',
    },
    info: {
        badge: 'Info',
        card: 'border-sky-200/80 bg-white/90 dark:border-sky-500/20 dark:bg-slate-900/90',
        glow: 'from-sky-500/20 via-sky-400/10 to-transparent',
        iconWrap: 'bg-sky-500/10 text-sky-600 ring-1 ring-sky-500/15 dark:bg-sky-500/15 dark:text-sky-300',
        title: 'text-sky-700 dark:text-sky-300',
        message: 'text-slate-600 dark:text-slate-300',
        progress: 'from-[#00AEEF] to-sky-500',
    },
    warning: {
        badge: 'Peringatan',
        card: 'border-amber-200/80 bg-white/90 dark:border-amber-500/20 dark:bg-slate-900/90',
        glow: 'from-amber-500/20 via-amber-400/10 to-transparent',
        iconWrap: 'bg-amber-500/10 text-amber-600 ring-1 ring-amber-500/15 dark:bg-amber-500/15 dark:text-amber-300',
        title: 'text-amber-700 dark:text-amber-300',
        message: 'text-slate-600 dark:text-slate-300',
        progress: 'from-amber-500 to-orange-500',
    },
    error: {
        badge: 'Gagal',
        card: 'border-rose-200/80 bg-white/90 dark:border-rose-500/20 dark:bg-slate-900/90',
        glow: 'from-rose-500/20 via-rose-400/10 to-transparent',
        iconWrap: 'bg-rose-500/10 text-rose-600 ring-1 ring-rose-500/15 dark:bg-rose-500/15 dark:text-rose-300',
        title: 'text-rose-700 dark:text-rose-300',
        message: 'text-slate-600 dark:text-slate-300',
        progress: 'from-rose-500 to-pink-500',
    },
};

const iconPath = (type) => {
    switch (type) {
        case 'success':
            return 'M9 12.75 11.25 15 15 9.75m6 2.25a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z';
        case 'warning':
            return 'M12 9v3.75m0 3.75h.008v.008H12v-.008ZM10.34 3.94 2.59 17.25A1.5 1.5 0 0 0 3.89 19.5h16.22a1.5 1.5 0 0 0 1.3-2.25L13.66 3.94a1.5 1.5 0 0 0-2.6 0Z';
        case 'error':
            return 'm9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z';
        default:
            return 'M11.25 11.25 12 11.25v5.25H12.75m-.75-8.25h.008v.008H12V8.25ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z';
    }
};

const pushFlashEntries = (value = {}) => {
    const entries = [];
    const token = value.token ?? null;

    if (value.success) {
        entries.push({
            type: 'success',
            title: 'Operasi berhasil',
            message: value.success,
        });
    }

    const statusMessage = normalizeStatusMessage(value.status);

    if (statusMessage) {
        entries.push({
            type: 'success',
            title: 'Operasi berhasil',
            message: statusMessage,
        });
    }

    if (value.info) {
        entries.push({
            type: 'info',
            title: 'Informasi terbaru',
            message: value.info,
        });
    }

    if (value.warning) {
        entries.push({
            type: 'warning',
            title: 'Perlu perhatian',
            message: value.warning,
        });
    }

    if (value.error) {
        entries.push({
            type: 'error',
            title: 'Terjadi kendala',
            message: value.error,
        });
    }

    if (!entries.length) {
        lastFlashToken.value = null;
        return;
    }

    if (token && token === lastFlashToken.value) {
        return;
    }

    lastFlashToken.value = token ?? JSON.stringify(entries);
    entries.forEach((entry) => notify(entry));
};

let removeSuccessListener = null;

onMounted(() => {
    pushFlashEntries(flash.value);

    removeSuccessListener = router.on('success', (page) => {
        pushFlashEntries(page?.props?.flash ?? page?.flash ?? {});
    });
});

onUnmounted(() => {
    removeSuccessListener?.();
});
</script>

<template>
    <Teleport to="body">
        <div class="pointer-events-none fixed inset-x-0 z-[70] flex justify-end px-4 sm:px-6 lg:px-8" :style="{ top: topOffset }" aria-live="polite">
            <TransitionGroup name="toast-stack" tag="div" class="flex w-full max-w-md flex-col gap-3">
                <section
                    v-for="notification in notifications"
                    :key="notification.id"
                    class="pointer-events-auto relative overflow-hidden rounded-[1.35rem] border shadow-[0_18px_48px_-28px_rgba(15,23,42,0.32)]"
                    :class="palette[notification.type]?.card"
                >
                    <div class="absolute inset-x-0 top-0 h-20 bg-gradient-to-br opacity-80" :class="palette[notification.type]?.glow" />

                    <div class="relative flex gap-4 p-4 sm:p-5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl" :class="palette[notification.type]?.iconWrap">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="iconPath(notification.type)" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-[11px] font-black uppercase tracking-[0.24em] text-slate-400 dark:text-slate-500">
                                        {{ palette[notification.type]?.badge }}
                                    </p>
                                    <p class="mt-1 text-sm font-extrabold" :class="palette[notification.type]?.title">
                                        {{ notification.title ?? 'Pembaruan sistem' }}
                                    </p>
                                </div>

                                <button
                                    type="button"
                                    class="rounded-full p-1 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200"
                                    @click="dismiss(notification.id)"
                                >
                                    <span class="sr-only">Tutup notifikasi</span>
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 18 12-12M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <p class="mt-2 text-sm leading-6" :class="palette[notification.type]?.message">
                                {{ notification.message }}
                            </p>
                        </div>
                    </div>

                    <div class="absolute inset-x-0 bottom-0 h-1 bg-slate-200/70 dark:bg-slate-800/80">
                        <div
                            class="h-full bg-gradient-to-r toast-progress"
                            :class="palette[notification.type]?.progress"
                            :style="{ animationDuration: `${notification.duration}ms` }"
                        />
                    </div>
                </section>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<style scoped>
.toast-stack-enter-active,
.toast-stack-leave-active {
    transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
}

.toast-stack-enter-from,
.toast-stack-leave-to {
    opacity: 0;
    transform: translateY(-12px) scale(0.96);
}

.toast-stack-move {
    transition: transform 0.18s cubic-bezier(0.16, 1, 0.3, 1);
}

.toast-progress {
    transform-origin: left center;
    animation-name: shrinkWidth;
    animation-timing-function: linear;
    animation-fill-mode: forwards;
}

@keyframes shrinkWidth {
    from {
        transform: scaleX(1);
    }

    to {
        transform: scaleX(0);
    }
}
</style>
