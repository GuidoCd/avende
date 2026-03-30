<template>
  <div :class="['absolute left-1/2 -translate-x-1/2 z-10 transition-all duration-300 ease-in-out', isFocused ? 'top-8 w-[96%] sm:w-[85%] md:w-[600px] lg:w-[700px]' : 'top-6 w-[92%] sm:w-[90%] md:w-[700px] lg:w-[850px]']">
    <div :class="['backdrop-blur-md rounded-full p-2 flex items-center justify-between transition-all duration-300 ease-in-out', isFocused ? 'bg-white shadow-[0_12px_40px_rgba(0,143,57,0.25)] ring-2 ring-[#008f39] dark:bg-zinc-900 dark:ring-emerald-500' : 'bg-white/80 dark:bg-zinc-900/80 border border-white/20 dark:border-zinc-800/50 shadow-[0_8px_30px_rgb(0,0,0,0.12)]']">
      
      <!-- Brand / Logo -->
      <div v-show="!isFocused" class="px-4 border-r border-gray-200 dark:border-zinc-700 hidden sm:flex items-center gap-2 transition-opacity">
        <div class="bg-emerald-500 text-white p-1 rounded-md">
           <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <span class="font-bold text-gray-900 dark:text-white whitespace-nowrap">Vive Aquí</span>
      </div>

      <!-- Search Input -->
      <div class="flex-1 flex items-center px-4 gap-2 text-gray-500 dark:text-gray-400">
        <button @click="handleSearch" class="focus:outline-none p-2 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-200 dark:hover:bg-emerald-800 rounded-full transition-colors cursor-pointer shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </button>
        <input 
          v-model="searchQuery"
          @focus="isFocused = true"
          @blur="handleBlur"
          @keyup.enter="handleSearch"
          type="text" 
          :placeholder="__('Search Location...')" 
          :class="['bg-transparent border-none outline-none focus:ring-0 w-full text-gray-900 dark:text-white placeholder-gray-400 flex-1 min-w-0 transition-all font-medium', isFocused ? 'text-lg md:text-xl px-2 py-1' : 'text-base']"
        />
      </div>

      <!-- Controls / Actions -->
      <div v-show="!isFocused" class="flex items-center gap-1 sm:gap-1.5 pr-1 shrink-0 transition-opacity">
        <!-- Filter Toggle -->
        <div class="relative">
          <button @click="isFiltersOpen = !isFiltersOpen" class="flex items-center gap-1 px-3 py-2 rounded-full hover:bg-emerald-50 text-emerald-700 dark:hover:bg-zinc-800 transition-colors text-sm font-medium dark:text-gray-200 whitespace-nowrap bg-emerald-100 dark:bg-emerald-900/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            <span class="hidden sm:inline">{{ __('Filter') }}</span>
          </button>
        </div>

        <!-- Auth State -->
        <template v-if="$page.props.auth?.user">
          <div class="relative">
            <button @click="isUserMenuOpen = !isUserMenuOpen" class="flex items-center gap-2 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 pl-2 pr-3 py-1.5 rounded-full shadow-sm hover:shadow-md transition-all">
              <div class="w-7 h-7 rounded-full bg-linear-to-br from-[#008f39] to-emerald-300 flex items-center justify-center text-white text-xs font-bold uppercase">
                {{ $page.props.auth.user.name.charAt(0) }}
              </div>
              <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            
            <!-- User Dropdown Menu -->
            <transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div v-if="isUserMenuOpen" class="absolute right-0 mt-2 w-56 rounded-xl shadow-lg bg-white dark:bg-zinc-800 ring-1 ring-black ring-opacity-5 dark:ring-white/10 overflow-hidden text-sm z-50">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-zinc-700">
                  <p class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ $page.props.auth.user.name }}</p>
                  <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $page.props.auth.user.email }}</p>
                </div>
                <div class="py-1">
                  <template v-if="$page.props.auth.roles?.includes('admin') || $page.props.auth.roles?.includes('support')">
                    <Link href="/admin-portal/dashboard" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-700 dark:hover:text-white gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                        {{ __('Admin Dashboard') }}
                    </Link>
                  </template>
                  <template v-else-if="$page.props.auth.roles?.includes('publisher')">
                    <Link href="/publisher/dashboard" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-700 dark:hover:text-white gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        {{ __('Publisher Dashboard') }}
                    </Link>
                  </template>
                  <template v-else>
                    <Link href="/profile" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-700 dark:hover:text-white gap-2 transition-colors">
                       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                       {{ __('My Account') }}
                    </Link>
                    <Link href="/profile/favorites" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-zinc-700 dark:hover:text-white gap-2 transition-colors">
                       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                       {{ __('My Favorites') }}
                    </Link>
                  </template>
                  <div class="border-t border-gray-100 dark:border-zinc-700 my-1"></div>
                  <Link href="/logout" method="post" as="button" class="w-full text-left flex items-center px-4 py-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 gap-2 transition-colors">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                     {{ __('Log Out') }}
                  </Link>
                </div>
              </div>
            </transition>
          </div>
        </template>
        <template v-else>
          <!-- Auth Toggle -->
          <button @click="$emit('open-auth')" class="bg-[#008f39] hover:bg-emerald-700 text-white px-3 sm:px-4 py-2 rounded-full shadow-md font-medium transition-colors whitespace-nowrap flex items-center gap-1.5 text-sm">
            <svg class="w-4 h-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            {{ __('Login') }}
          </button>
        </template>

        <!-- Divisor -->
        <div class="w-px h-6 bg-gray-200 dark:bg-zinc-700 mx-1 hidden sm:block"></div>

        <!-- Language Selector -->
        <div class="relative">
          <button @click="isLangMenuOpen = !isLangMenuOpen" class="flex items-center gap-1 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors text-gray-500 dark:text-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
            <span class="text-xs font-semibold uppercase hidden sm:block">{{ currentLocale }}</span>
          </button>

          <!-- Language Dropdown Menu -->
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
        <button @click="toggleDarkMode" class="p-2 ml-1 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors text-gray-500 dark:text-gray-400">
           <svg v-if="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
           <svg v-else class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>
      </div>
    </div>
    <!-- Dropdown Filters Menu -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-2"
    >
      <div v-if="isFiltersOpen" class="absolute top-[110%] w-full bg-white/95 dark:bg-zinc-900/95 backdrop-blur-md rounded-2xl shadow-xl border border-gray-100 dark:border-zinc-800 p-4 z-50">
        
        <!-- Types -->
        <div class="flex items-center gap-3 mb-4 overflow-x-auto pb-3 scrollbar-thin scrollbar-thumb-gray-200 dark:scrollbar-thumb-zinc-700 scrollbar-track-transparent">
          <span class="text-xs font-bold text-gray-500 uppercase tracking-wider shrink-0 w-14">{{ __('Property Type') }}</span>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium bg-[#008f39] text-white shrink-0 shadow-sm">{{ __('All Types') }}</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">{{ __('Apartment') }}</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">{{ __('House') }}</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">Penthouse</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">{{ __('Studio') }}</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">Townhouse</button>
        </div>

        <!-- Prices -->
        <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-200 dark:scrollbar-thumb-zinc-700 scrollbar-track-transparent">
          <span class="text-xs font-bold text-gray-500 uppercase tracking-wider shrink-0 w-14">{{ __('Price') }}</span>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium bg-[#00a8cc] text-white shrink-0">{{ __('Any price') }}</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">&lt; $15k/{{ __('mes') }}</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">$15k–$40k/{{ __('mes') }}</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">&gt; $40k/{{ __('mes') }}</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">{{ __('Sale') }}</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">{{ __('Sale') }}</button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup lang="ts">
import { Link, usePage, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';

declare const __: any;

const emit = defineEmits(['open-auth', 'search', 'location-searched']);

const page = usePage();
const currentLocale = computed(() => page.props.locale || 'es');
const searchQuery = ref((page.props.filters as Record<string, any> | undefined)?.search || '');

const isFiltersOpen = ref(false);
const isUserMenuOpen = ref(false);
const isLangMenuOpen = ref(false);
const isDark = ref(false);
const isFocused = ref(false);

const handleBlur = () => {
  // Retrasamos el blur por si se hizo click en el botón de búsqueda y para suavizar
  setTimeout(() => {
    isFocused.value = false;
  }, 200);
};

const changeLanguage = (lang: string) => {
  isLangMenuOpen.value = false;
  router.post('/language', { locale: lang }, {
    preserveScroll: true,
    preserveState: false, // Force a hard refresh of Inertia state
  });
};

const handleSearch = async () => {
  if (!searchQuery.value) return;
  isFocused.value = false;
  try {
    const mapboxToken = import.meta.env.VITE_MAPBOX_TOKEN;
    const res = await fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(searchQuery.value)}.json?access_token=${mapboxToken}`);
    const data = await res.json();
    if (data.features && data.features.length > 0) {
      const [lng, lat] = data.features[0].center;
      emit('location-searched', { lng, lat, term: searchQuery.value });
    }
  } catch (e) {
    console.error('Failed to geocode search', e);
  }
};

const closeDropdowns = (e: MouseEvent) => {
  const target = e.target as HTMLElement;
  if (isUserMenuOpen.value && !target.closest('.relative')) {
    isUserMenuOpen.value = false;
  }
  if (isLangMenuOpen.value && !target.closest('.relative')) {
    isLangMenuOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', closeDropdowns);
  // Check initial theme
  if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    // We can conditionally set this based on preferences, for now default to light
  }
  // Check localStorage or initial class
  isDark.value = document.documentElement.classList.contains('dark');
});

onUnmounted(() => {
  document.removeEventListener('click', closeDropdowns);
});

const toggleDarkMode = () => {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
};
</script>
