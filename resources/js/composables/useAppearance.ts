import type { ComputedRef, Ref } from 'vue';
import { computed, onMounted, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { Appearance, ResolvedAppearance } from '@/types';

export type { Appearance, ResolvedAppearance };

export type UseAppearanceReturn = {
    appearance: Ref<Appearance>;
    resolvedAppearance: ComputedRef<ResolvedAppearance>;
    updateAppearance: (value: Appearance) => void;
};

export function updateTheme(value: Appearance): void {
    if (typeof window === 'undefined') {
        return;
    }

    if (value === 'system') {
        const mediaQueryList = window.matchMedia(
            '(prefers-color-scheme: dark)',
        );
        const systemTheme = mediaQueryList.matches ? 'dark' : 'light';

        document.documentElement.classList.toggle(
            'dark',
            systemTheme === 'dark',
        );
    } else {
        document.documentElement.classList.toggle('dark', value === 'dark');
    }
}

const setCookie = (name: string, value: string, days = 365) => {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;

    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

const mediaQuery = () => {
    if (typeof window === 'undefined') {
        return null;
    }

    return window.matchMedia('(prefers-color-scheme: dark)');
};

const getStoredAppearance = () => {
    if (typeof window === 'undefined') {
        return null;
    }

    return localStorage.getItem('appearance') as Appearance | null;
};

const prefersDark = (): boolean => {
    if (typeof window === 'undefined') {
        return false;
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches;
};

const handleSystemThemeChange = () => {
    const currentAppearance = getStoredAppearance();

    updateTheme(currentAppearance || 'system');
};

export function initializeTheme(): void {
    if (typeof window === 'undefined') {
        return;
    }

    // Initialize theme from saved preference or default to system...
    const savedAppearance = getStoredAppearance();
    updateTheme(savedAppearance || 'system');

    // Set up system theme change listener...
    mediaQuery()?.addEventListener('change', handleSystemThemeChange);
}

const appearance = ref<Appearance>('system');

export function useAppearance(): UseAppearanceReturn {
    onMounted(() => {
        let savedAppearance = null;
        
        try {
            const page = usePage() as any;
            // Try to get theme from authenticated user's preferences first
            if (page?.props?.auth?.user?.preferences?.theme) {
                savedAppearance = page.props.auth.user.preferences.theme as Appearance;
            }
        } catch (e) {
            // usePage might fail if not fully inside a Vue component context sometimes, fallback safely
        }

        // Fallback to localStorage
        if (!savedAppearance) {
            savedAppearance = localStorage.getItem('appearance') as Appearance | null;
        }

        if (savedAppearance) {
            appearance.value = savedAppearance;
            updateTheme(savedAppearance); // Ensure dom follows backend truth
        }
    });

    const resolvedAppearance = computed<ResolvedAppearance>(() => {
        if (appearance.value === 'system') {
            return prefersDark() ? 'dark' : 'light';
        }

        return appearance.value;
    });

    function updateAppearance(value: Appearance) {
        appearance.value = value;

        // Store in localStorage for client-side persistence...
        localStorage.setItem('appearance', value);

        // Store in cookie for SSR...
        setCookie('appearance', value);

        updateTheme(value);

        if (typeof window !== 'undefined') {
            import('@inertiajs/vue3').then(({ router }) => {
                router.post('/preferences', { theme: value }, {
                    preserveScroll: true,
                    preserveState: true,
                    onError: () => {}
                });
            });
        }
    }

    return {
        appearance,
        resolvedAppearance,
        updateAppearance,
    };
}
