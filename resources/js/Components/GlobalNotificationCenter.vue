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
        shell: 'border-emerald-200/70 bg-[linear-gradient(135deg,rgba(255,255,255,0.98),rgba(240,253,244,0.92))] dark:border-emerald-500/20 dark:bg-[linear-gradient(135deg,rgba(8,20,38,0.98),rgba(6,78,59,0.14))]',
        rail: 'from-emerald-400 via-[#00A651] to-emerald-600',
        iconWrap: 'bg-emerald-500/10 text-emerald-600 ring-1 ring-emerald-500/15 dark:bg-emerald-500/12 dark:text-emerald-300',
        badgeWrap: 'bg-emerald-500/10 text-emerald-700 ring-1 ring-emerald-500/15 dark:bg-emerald-500/12 dark:text-emerald-300',
        title: 'text-emerald-800 dark:text-emerald-200',
        message: 'text-slate-600 dark:text-slate-300',
        accent: 'text-emerald-500/70 dark:text-emerald-300/70',
        progress: 'from-emerald-400 via-[#00A651] to-emerald-600',
    },
    info: {
        badge: 'Info',
        shell: 'border-sky-200/70 bg-[linear-gradient(135deg,rgba(255,255,255,0.98),rgba(239,246,255,0.94))] dark:border-sky-500/20 dark:bg-[linear-gradient(135deg,rgba(8,20,38,0.98),rgba(2,132,199,0.14))]',
        rail: 'from-sky-400 via-[#00AEEF] to-cyan-500',
        iconWrap: 'bg-sky-500/10 text-sky-600 ring-1 ring-sky-500/15 dark:bg-sky-500/12 dark:text-sky-300',
        badgeWrap: 'bg-sky-500/10 text-sky-700 ring-1 ring-sky-500/15 dark:bg-sky-500/12 dark:text-sky-300',
        title: 'text-sky-800 dark:text-sky-200',
        message: 'text-slate-600 dark:text-slate-300',
        accent: 'text-sky-500/70 dark:text-sky-300/70',
        progress: 'from-sky-400 via-[#00AEEF] to-cyan-500',
    },
    warning: {
        badge: 'Peringatan',
        shell: 'border-amber-200/70 bg-[linear-gradient(135deg,rgba(255,255,255,0.98),rgba(255,251,235,0.94))] dark:border-amber-500/20 dark:bg-[linear-gradient(135deg,rgba(8,20,38,0.98),rgba(217,119,6,0.14))]',
        rail: 'from-amber-400 via-orange-500 to-amber-600',
        iconWrap: 'bg-amber-500/10 text-amber-600 ring-1 ring-amber-500/15 dark:bg-amber-500/12 dark:text-amber-300',
        badgeWrap: 'bg-amber-500/10 text-amber-700 ring-1 ring-amber-500/15 dark:bg-amber-500/12 dark:text-amber-300',
        title: 'text-amber-800 dark:text-amber-200',
        message: 'text-slate-600 dark:text-slate-300',
        accent: 'text-amber-500/70 dark:text-amber-300/70',
        progress: 'from-amber-400 via-orange-500 to-amber-600',
    },
    error: {
        badge: 'Gagal',
        shell: 'border-rose-200/70 bg-[linear-gradient(135deg,rgba(255,255,255,0.98),rgba(255,241,242,0.94))] dark:border-rose-500/20 dark:bg-[linear-gradient(135deg,rgba(8,20,38,0.98),rgba(225,29,72,0.14))]',
        rail: 'from-rose-400 via-rose-500 to-pink-600',
        iconWrap: 'bg-rose-500/10 text-rose-600 ring-1 ring-rose-500/15 dark:bg-rose-500/12 dark:text-rose-300',
        badgeWrap: 'bg-rose-500/10 text-rose-700 ring-1 ring-rose-500/15 dark:bg-rose-500/12 dark:text-rose-300',
        title: 'text-rose-800 dark:text-rose-200',
        message: 'text-slate-600 dark:text-slate-300',
        accent: 'text-rose-500/70 dark:text-rose-300/70',
        progress: 'from-rose-400 via-rose-500 to-pink-600',
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

let removeFlashListener = null;
let removeSuccessListener = null;

onMounted(() => {
    pushFlashEntries(flash.value);

    removeSuccessListener = router.on('success', (event) => {
        pushFlashEntries(event.detail.page?.props?.flash ?? {});
    });

    removeFlashListener = router.on('flash', (event) => {
        pushFlashEntries(event.detail.flash ?? {});
    });
});

onUnmounted(() => {
    removeSuccessListener?.();
    removeFlashListener?.();
});
</script>

<template>
    <Teleport to="body">
        <div class="pointer-events-none fixed inset-x-0 z-[70] flex justify-end px-4 sm:px-6 lg:px-8" :style="{ top: topOffset }" aria-live="polite">
            <TransitionGroup name="toast-stack" tag="div" class="flex w-full max-w-[21rem] flex-col gap-2.5">
                <section
                    v-for="notification in notifications"
                    :key="notification.id"
                    class="pointer-events-auto relative overflow-hidden rounded-[1.1rem] border shadow-[0_10px_24px_-18px_rgba(15,23,42,0.16)]"
                    :class="palette[notification.type]?.shell"
                >
                    <div class="absolute inset-y-0 left-0 w-1.5 bg-gradient-to-b" :class="palette[notification.type]?.rail" />
                    <div class="absolute inset-y-0 right-0 w-24 opacity-40 dark:opacity-30">
                        <div class="toast-grid h-full w-full" :class="palette[notification.type]?.accent" />
                    </div>

                    <div class="relative flex gap-3 p-3.5">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-[0.9rem]" :class="palette[notification.type]?.iconWrap">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="iconPath(notification.type)" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <div class="inline-flex items-center rounded-full px-2 py-1 text-[9px] font-black uppercase tracking-[0.22em]" :class="palette[notification.type]?.badgeWrap">
                                        {{ palette[notification.type]?.badge }}
                                    </div>
                                    <p class="mt-2 text-[13px] font-extrabold tracking-tight" :class="palette[notification.type]?.title">
                                        {{ notification.title ?? 'Pembaruan sistem' }}
                                    </p>
                                    <p class="mt-1.5 text-[12px] leading-5" :class="palette[notification.type]?.message">
                                        {{ notification.message }}
                                    </p>
                                </div>

                                <button
                                    type="button"
                                    class="rounded-full p-1 text-slate-400 transition-colors hover:bg-white/70 hover:text-slate-700 dark:hover:bg-slate-800/70 dark:hover:text-slate-200"
                                    @click="dismiss(notification.id)"
                                >
                                    <span class="sr-only">Tutup notifikasi</span>
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 18 12-12M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="absolute inset-x-0 bottom-0 h-[3px] bg-slate-200/55 dark:bg-slate-800/70">
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
    transition: all 0.12s cubic-bezier(0.16, 1, 0.3, 1);
}

.toast-stack-enter-from,
.toast-stack-leave-to {
    opacity: 0;
    transform: translateY(-8px) scale(0.98);
}

.toast-stack-move {
    transition: transform 0.12s cubic-bezier(0.16, 1, 0.3, 1);
}

.toast-progress {
    transform-origin: left center;
    animation-name: shrinkWidth;
    animation-timing-function: linear;
    animation-fill-mode: forwards;
}

.toast-grid {
    background-image:
        linear-gradient(currentColor 1px, transparent 1px),
        linear-gradient(90deg, currentColor 1px, transparent 1px);
    background-size: 14px 14px;
    mask-image: linear-gradient(90deg, transparent, black 30%, black);
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
