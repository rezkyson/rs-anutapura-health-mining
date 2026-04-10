import { reactive } from 'vue';

const notifications = reactive([]);
const timers = new Map();

const DEFAULT_DURATION = {
    success: 4200,
    info: 4800,
    warning: 5600,
    error: 6400,
};

let nextNotificationId = 0;

const clearTimer = (id) => {
    const timer = timers.get(id);

    if (timer) {
        clearTimeout(timer);
        timers.delete(id);
    }
};

const dismiss = (id) => {
    const index = notifications.findIndex((notification) => notification.id === id);

    if (index === -1) {
        return;
    }

    clearTimer(id);
    notifications.splice(index, 1);
};

const scheduleDismiss = (notification) => {
    clearTimer(notification.id);

    if (notification.persistent) {
        return;
    }

    const timer = window.setTimeout(() => {
        dismiss(notification.id);
    }, notification.duration);

    timers.set(notification.id, timer);
};

const normalizeNotification = (messageOrOptions, overrides = {}) => {
    const base = typeof messageOrOptions === 'string'
        ? { message: messageOrOptions }
        : (messageOrOptions ?? {});

    const notification = {
        id: ++nextNotificationId,
        type: base.type ?? overrides.type ?? 'info',
        title: base.title ?? overrides.title ?? null,
        message: base.message ?? overrides.message ?? '',
        duration: base.duration ?? overrides.duration ?? DEFAULT_DURATION[base.type ?? overrides.type ?? 'info'],
        persistent: base.persistent ?? overrides.persistent ?? false,
    };

    return notification;
};

const notify = (messageOrOptions, overrides = {}) => {
    const notification = normalizeNotification(messageOrOptions, overrides);

    notifications.unshift(notification);

    if (notifications.length > 5) {
        const removed = notifications.splice(5);
        removed.forEach((item) => clearTimer(item.id));
    }

    scheduleDismiss(notification);

    return notification.id;
};

const success = (messageOrOptions, overrides = {}) => notify(messageOrOptions, { ...overrides, type: 'success' });
const info = (messageOrOptions, overrides = {}) => notify(messageOrOptions, { ...overrides, type: 'info' });
const warning = (messageOrOptions, overrides = {}) => notify(messageOrOptions, { ...overrides, type: 'warning' });
const error = (messageOrOptions, overrides = {}) => notify(messageOrOptions, { ...overrides, type: 'error', duration: overrides.duration ?? 6400 });
const clear = () => {
    notifications.forEach((notification) => clearTimer(notification.id));
    notifications.splice(0, notifications.length);
};

export function useNotifications() {
    return {
        notifications,
        notify,
        success,
        info,
        warning,
        error,
        dismiss,
        clear,
    };
}
