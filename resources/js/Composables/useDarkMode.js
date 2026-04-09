import { ref, watch, onMounted } from 'vue';

const isDark = ref(false);

export function useDarkMode() {
    // Fungsi untuk mengubah tema
    const toggleDark = () => {
        isDark.value = !isDark.value;
    };

    onMounted(() => {
        // Cek localStorage atau preferensi sistem browser
        if (
            localStorage.getItem('theme') === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            isDark.value = true;
            document.documentElement.classList.add('dark');
        } else {
            isDark.value = false;
            document.documentElement.classList.remove('dark');
        }
    });

    // Pantau perubahan dari value isDark, kemudian terapkan ke class HTML dan localStorage
    watch(isDark, (newValue) => {
        if (newValue) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    });

    return {
        isDark,
        toggleDark,
    };
}
