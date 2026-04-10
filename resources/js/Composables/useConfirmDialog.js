import { reactive } from 'vue';

const defaultState = {
    open: false,
    title: 'Konfirmasi tindakan',
    message: 'Apakah Anda yakin ingin melanjutkan proses ini?',
    confirmLabel: 'Ya, lanjutkan',
    loadingLabel: 'Memproses permintaan...',
    cancelLabel: 'Batal',
    tone: 'primary',
};

const state = reactive({
    ...defaultState,
    resolver: null,
});

const resetState = () => {
    Object.assign(state, defaultState, {
        open: false,
        resolver: null,
    });
};

const settle = (result) => {
    const resolver = state.resolver;

    resetState();

    if (resolver) {
        resolver(result);
    }
};

const confirm = (options = {}) => {
    if (state.resolver) {
        state.resolver(false);
    }

    Object.assign(state, defaultState, options, {
        open: true,
    });

    return new Promise((resolve) => {
        state.resolver = resolve;
    });
};

export function useConfirmDialog() {
    return {
        confirmState: state,
        confirm,
        approve: () => settle(true),
        cancel: () => settle(false),
    };
}
