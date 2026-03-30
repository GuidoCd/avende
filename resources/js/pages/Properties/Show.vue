<script setup lang="ts">
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { ChevronRight, Home, MapPin, ArrowLeft } from 'lucide-vue-next';

// Components
import PropertyGallery from '../../components/detail/PropertyGallery.vue';
import PropertySpecs from '../../components/detail/PropertySpecs.vue';
import PropertyLocationMap from '../../components/detail/PropertyLocationMap.vue';
import PriceStickyCard from '../../components/detail/PriceStickyCard.vue';
import AgentContactCard from '../../components/detail/AgentContactCard.vue';
import AuthModal from '../../components/AuthModal.vue';

declare const __: any;

const props = defineProps<{
    property: any;
    canLogin?: boolean;
    canRegister?: boolean;
}>();
const page = usePage();

const TYPE_LABELS: Record<string, string> = {
    apartment: "Departamento",
    house: "Casa",
    penthouse: "Penthouse",
    studio: "Studio",
    townhouse: "Townhouse",
    // fallbacks
    propiedad: "Propiedad"
};

const isAuthModalOpen = ref(false);

const openAuthModal = () => {
    isAuthModalOpen.value = true;
};

// --- Settings and Display State ---
const currentLocale = computed(() => page.props.locale || 'es');
const isLangMenuOpen = ref(false);
const isDark = ref(false);

const changeLanguage = (lang: string) => {
  isLangMenuOpen.value = false;
  router.post('/language', { locale: lang }, {
    preserveScroll: true,
    preserveState: false, 
  });
};

const closeDropdowns = (e: Event) => {
  if (isLangMenuOpen.value && !(e.target as Element).closest('.relative-lang')) {
    isLangMenuOpen.value = false;
  }
};

const toggleDarkMode = () => {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
};

onMounted(() => {
  document.addEventListener('click', closeDropdowns);
  isDark.value = document.documentElement.classList.contains('dark');
});

onUnmounted(() => {
  document.removeEventListener('click', closeDropdowns);
});
</script>

<template>
    <Head>
        <title>{{ property.title }} - Vive Aquí</title>
    </Head>

    <div class="min-h-screen bg-gray-50 dark:bg-zinc-950 font-['Inter',sans-serif]">
        <!-- Top nav bar -->
        <header class="sticky top-0 z-40 bg-white/80 dark:bg-zinc-950/80 backdrop-blur-md border-b border-gray-200 dark:border-zinc-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between gap-4">
                <!-- Logo + back -->
                <div class="flex items-center gap-3">
                    <Link
                        href="/"
                        class="flex items-center gap-2 text-sm font-bold text-[#008f39] hover:text-[#008f39]/80 transition-colors"
                        aria-label="Volver al mapa"
                    >
                        <div class="w-7 h-7 rounded-lg bg-[#008f39] flex items-center justify-center">
                            <Home :size="13" class="text-white" />
                        </div>
                        <span class="hidden sm:inline">Vive Aquí</span>
                    </Link>

                    <!-- Breadcrumb -->
                    <nav aria-label="Breadcrumb" class="hidden md:flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                        <ChevronRight :size="12" class="opacity-40" />
                        <Link href="/" class="hover:text-gray-900 dark:hover:text-white transition-colors">Inicio</Link>
                        <ChevronRight :size="12" class="opacity-40" />
                        <span class="text-gray-500 dark:text-gray-400">{{ TYPE_LABELS[property.type.toLowerCase()] || property.type || "Propiedad" }}</span>
                        <ChevronRight :size="12" class="opacity-40" />
                        <span class="text-gray-900 dark:text-white font-medium truncate max-w-[180px]">{{ property.neighborhood }}</span>
                    </nav>
                </div>

                <!-- Back button mobile -->
                <Link
                    href="/"
                    class="md:hidden flex items-center gap-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
                >
                    <ArrowLeft :size="14" />
                    Volver
                </Link>

                <!-- Right Actions: Toggles & Profile Button -->
                <div class="flex items-center gap-1 sm:gap-2 ml-auto">
                    
                    <!-- Language Toggle -->
                    <div class="relative relative-lang hidden sm:block">
                        <button @click.stop="isLangMenuOpen = !isLangMenuOpen" class="flex items-center gap-1 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors text-gray-500 dark:text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                            <span class="text-xs font-semibold uppercase">{{ currentLocale }}</span>
                        </button>
                        
                        <transition
                            enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <div v-if="isLangMenuOpen" class="absolute right-0 mt-2 w-32 rounded-xl shadow-lg bg-white dark:bg-zinc-800 ring-1 ring-black ring-opacity-5 dark:ring-white/10 overflow-hidden text-sm z-50">
                            <div class="py-1">
                                <button @click="changeLanguage('es')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors flex items-center justify-between" :class="{'text-[#008f39] font-bold': currentLocale === 'es', 'text-gray-700 dark:text-gray-300': currentLocale !== 'es'}">
                                Español <span v-if="currentLocale === 'es'">✓</span>
                                </button>
                                <button @click="changeLanguage('en')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors flex items-center justify-between" :class="{'text-[#008f39] font-bold': currentLocale === 'en', 'text-gray-700 dark:text-gray-300': currentLocale !== 'en'}">
                                English <span v-if="currentLocale === 'en'">✓</span>
                                </button>
                                <button @click="changeLanguage('pt')" class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors flex items-center justify-between" :class="{'text-[#008f39] font-bold': currentLocale === 'pt', 'text-gray-700 dark:text-gray-300': currentLocale !== 'pt'}">
                                Português <span v-if="currentLocale === 'pt'">✓</span>
                                </button>
                            </div>
                            </div>
                        </transition>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button @click="toggleDarkMode" class="p-2 ml-1 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors text-gray-500 dark:text-gray-400 hidden sm:block">
                        <svg v-if="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg v-else class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>

                    <!-- User Action -->
                    <div v-if="page.props.auth?.user" class="hidden md:flex">
                        <template v-if="page.props.auth.roles?.includes('admin') || page.props.auth.roles?.includes('support')">
                            <Link href="/admin-portal/dashboard" class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-sm font-semibold text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                                {{ __('Dashboard Admin') }}
                            </Link>
                        </template>
                        <template v-else-if="page.props.auth.roles?.includes('publisher')">
                            <Link href="/publisher/dashboard" class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-sm font-semibold text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                {{ __('Dashboard') }}
                            </Link>
                        </template>
                        <template v-else>
                            <Link href="/profile" class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-sm font-semibold text-gray-700 dark:text-gray-300 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                {{ __('Mi Perfil') }}
                            </Link>
                        </template>
                    </div>
                    <div v-else class="hidden md:flex items-center gap-2">
                        <button @click="openAuthModal" class="px-4 py-1.5 text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                            {{ __('Iniciar sesión') }}
                        </button>
                        <button @click="openAuthModal" class="px-4 py-1.5 bg-[#008f39] text-white text-sm font-bold rounded-full shadow-sm hover:bg-emerald-700 transition-colors">
                            {{ __('Registrarse') }}
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6">
            <!-- Title row -->
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-[#008f39]/10 text-[#008f39] border border-[#008f39]/20">
                            {{ TYPE_LABELS[property.type.toLowerCase()] || property.type || __("Propiedad") }}
                        </span>
                        <span v-if="property.status" class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-200 dark:bg-indigo-950/20 dark:text-indigo-400 dark:border-indigo-800">
                            <template v-if="property.status === 'sale'">{{ __('En Venta') }}</template>
                            <template v-else-if="property.status === 'rent'">{{ __('En Alquiler') }}</template>
                            <template v-else-if="property.status === 'anticretico'">{{ __('Anticrético') }}</template>
                            <template v-else>{{ __(property.status) }}</template>
                        </span>
                        <span v-if="property.featured" class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-800">
                            {{ __('Destacada') }}
                        </span>
                        <span v-if="property.newListing" class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-600 border border-blue-200 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-800">
                            {{ __('Nueva') }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white text-balance leading-tight">
                        {{ property.title }}
                    </h1>
                    <div class="flex items-center gap-1.5 mt-1.5 text-sm text-gray-500 dark:text-gray-400">
                        <MapPin :size="13" class="text-[#008f39] flex-shrink-0" />
                        <span>{{ property.address }}</span>
                    </div>
                </div>
            </div>

            <!-- Gallery -->
            <PropertyGallery :images="property.images" :title="property.title" />

            <!-- Two-column layout -->
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-6 items-start">
                <!-- LEFT — main content -->
                <div class="space-y-6">
                    <!-- Specs, description, amenities -->
                    <PropertySpecs :property="property" />

                    <!-- Location map -->
                    <PropertyLocationMap
                        :lat="property.lat"
                        :lng="property.lng"
                        :address="property.address"
                        :neighborhood="property.neighborhood"
                    />
                </div>

                <!-- RIGHT — sticky sidebar -->
                <div class="space-y-4 lg:sticky lg:top-20">
                    <PriceStickyCard :property="property" />
                    <AgentContactCard 
                        :agent="property.agent" 
                        :propertyTitle="property.title" 
                        :propertyId="property.id"
                        @requireAuth="openAuthModal" 
                    />
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="mt-12 border-t border-gray-200 dark:border-zinc-800 py-6 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                &copy; {{ new Date().getFullYear() }} Vive Aquí Inmobiliaria &middot; Todos los derechos reservados
            </p>
        </footer>

        <!-- Auth Modal -->
        <AuthModal :is-open="isAuthModalOpen" @close="isAuthModalOpen = false">
            <template #custom-message>
                <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 p-3 rounded-lg text-sm mb-4">
                    Para proteger la privacidad de nuestros anunciantes, necesitas iniciar sesión o registrarte (¡es gratis y rápido!) para ver los datos de contacto o enviar un mensaje.
                </div>
            </template>
        </AuthModal>
    </div>
</template>
