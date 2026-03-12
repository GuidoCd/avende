<template>
  <div class="absolute top-6 left-1/2 -translate-x-1/2 z-10 w-[90%] md:w-[600px]">
    <div class="bg-white/80 backdrop-blur-md dark:bg-zinc-900/80 border border-white/20 dark:border-zinc-800/50 shadow-[0_8px_30px_rgb(0,0,0,0.12)] rounded-full p-2 flex items-center justify-between transition-all duration-300">
      
      <!-- Brand / Logo -->
      <div class="px-4 border-r border-gray-200 dark:border-zinc-700 hidden sm:flex items-center gap-2">
        <div class="bg-emerald-500 text-white p-1 rounded-md">
           <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <span class="font-bold text-gray-900 dark:text-white whitespace-nowrap">Vive Aquí</span>
      </div>

      <!-- Search Input -->
      <div class="flex-1 flex items-center px-4 gap-2 text-gray-500 dark:text-gray-400">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <input 
          type="text" 
          placeholder="Busca por colonia, dirección o código postal..." 
          class="bg-transparent border-none outline-none focus:ring-0 w-full text-gray-900 dark:text-white placeholder-gray-400 flex-1 min-w-0"
        />
      </div>

      <!-- Controls -->
      <div class="flex items-center gap-1.5 pr-1">
        <!-- Filter Toggle -->
        <div class="relative">
          <button @click="isFiltersOpen = !isFiltersOpen" class="flex items-center gap-1 px-3 py-2 rounded-full hover:bg-emerald-50 text-emerald-700 dark:hover:bg-zinc-800 transition-colors text-sm font-medium dark:text-gray-200 whitespace-nowrap bg-emerald-100 dark:bg-emerald-900/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            <span class="hidden sm:inline">Filtros</span>
          </button>
        </div>

        <!-- Auth Toggle -->
        <button @click="$emit('open-auth')" class="bg-[#008f39] hover:bg-emerald-700 text-white px-4 py-2 rounded-full shadow-md font-medium transition-colors whitespace-nowrap flex items-center gap-1.5 text-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
          Ingresar
        </button>

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
          <span class="text-xs font-bold text-gray-500 uppercase tracking-wider shrink-0 w-14">Tipo</span>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium bg-[#008f39] text-white shrink-0 shadow-sm">Todos</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">Apartamento</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">Casa</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">Penthouse</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">Studio</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">Townhouse</button>
        </div>

        <!-- Prices -->
        <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-200 dark:scrollbar-thumb-zinc-700 scrollbar-track-transparent">
          <span class="text-xs font-bold text-gray-500 uppercase tracking-wider shrink-0 w-14">Precio</span>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium bg-[#00a8cc] text-white shrink-0">Cualquier precio</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">< $15k/mes</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">$15k–$40k/mes</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">> $40k/mes</button>
          <button class="px-4 py-1.5 rounded-full text-sm font-medium border border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 shrink-0">Venta</button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

defineEmits(['open-auth', 'search']);

const isFiltersOpen = ref(false);
const isDark = ref(false);

onMounted(() => {
  // Check initial theme
  if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    // We can conditionally set this based on preferences, for now default to light
  }
  // Check localStorage or initial class
  isDark.value = document.documentElement.classList.contains('dark');
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
